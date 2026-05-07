<?php
/**
 * Description: Keeps WooCommerce product prices identical across Polylang translations without Polylang for WooCommerce.
 */

defined('ABSPATH') || exit;

final class SyncWCPricesPolylang
{
    private static bool $syncing = false;

    public static function init(): void
    {
        // When creating a new translation using the "+" button Polylang.
        add_action('save_post_product', [self::class, 'copyPricesFromSourceOnCreate'], 50, 3);

        // When updating the price of an existing product.
        add_action('woocommerce_update_product', [self::class, 'syncProductPrices'], 100, 2);

        // When updating the price of a product variation.
        add_action('woocommerce_save_product_variation', [self::class, 'syncVariationPrices'], 100, 2);
    }

    public static function copyPricesFromSourceOnCreate(int $postId, WP_Post $post, bool $update): void
    {
        if (
            self::$syncing ||
            $update ||
            wp_is_post_autosave($postId) ||
            wp_is_post_revision($postId) ||
            ! current_user_can('edit_post', $postId) ||
            ! self::canSync()
        ) {
            return;
        }

        $sourceProductId = isset($_GET['from_post']) ? absint($_GET['from_post']) : 0;

        if (! $sourceProductId || $sourceProductId === $postId) {
            return;
        }

        $sourceProduct = wc_get_product($sourceProductId);
        $targetProduct = wc_get_product($postId);

        if (! $sourceProduct || ! $targetProduct) {
            return;
        }

        self::$syncing = true;

        if ($sourceProduct->is_type('variable') && $targetProduct->is_type('variable')) {
            self::copyVariablePricesByPosition($sourceProduct, $targetProduct);
        } else {
            self::copyPrices($sourceProduct, $targetProduct);
            $targetProduct->save();
        }

        wc_delete_product_transients($postId);

        self::$syncing = false;
    }

    public static function syncProductPrices(int $productId, WC_Product $product): void
    {
        if (self::$syncing || ! self::canSync()) {
            return;
        }

        if ($product->is_type('variable')) {
            self::syncVariableProductPrices($productId);
            return;
        }

        self::syncSimpleProductPrices($productId, $product);
    }

    public static function syncVariationPrices(int $variationId, int $loop): void
    {
        if (self::$syncing || ! self::canSync()) {
            return;
        }

        $variation = wc_get_product($variationId);

        if (! $variation || ! $variation->is_type('variation')) {
            return;
        }

        self::$syncing = true;

        self::syncVariationByTranslations($variationId, $variation);
        self::syncVariationByParentAndPosition($variationId, $variation);

        self::$syncing = false;
    }

    private static function syncSimpleProductPrices(int $productId, WC_Product $product): void
    {
        $translations = pll_get_post_translations($productId);

        if (empty($translations)) {
            return;
        }

        self::$syncing = true;

        foreach ($translations as $translatedProductId) {
            $translatedProductId = (int) $translatedProductId;

            if ($translatedProductId === $productId) {
                continue;
            }

            $translatedProduct = wc_get_product($translatedProductId);

            if (! $translatedProduct || $translatedProduct->is_type('variable')) {
                continue;
            }

            self::copyPrices($product, $translatedProduct);

            $translatedProduct->save();
            wc_delete_product_transients($translatedProductId);
        }

        wc_delete_product_transients($productId);

        self::$syncing = false;
    }

    private static function syncVariableProductPrices(int $productId): void
    {
        $product = wc_get_product($productId);

        if (! $product || ! $product->is_type('variable')) {
            return;
        }

        $sourceVariationIds = $product->get_children();

        foreach ($sourceVariationIds as $sourceVariationId) {
            $sourceVariation = wc_get_product($sourceVariationId);

            if (! $sourceVariation) {
                continue;
            }

            self::$syncing = true;

            self::syncVariationByTranslations((int) $sourceVariationId, $sourceVariation);
            self::syncVariationByParentAndPosition((int) $sourceVariationId, $sourceVariation);

            self::$syncing = false;
        }

        self::resyncVariableParents($productId);
    }

    private static function syncVariationByTranslations(int $variationId, WC_Product $variation): void
    {
        $translations = pll_get_post_translations($variationId);

        if (empty($translations)) {
            return;
        }

        foreach ($translations as $translatedVariationId) {
            $translatedVariationId = (int) $translatedVariationId;

            if ($translatedVariationId === $variationId) {
                continue;
            }

            $translatedVariation = wc_get_product($translatedVariationId);

            if (! $translatedVariation || ! $translatedVariation->is_type('variation')) {
                continue;
            }

            self::copyPrices($variation, $translatedVariation);

            $translatedVariation->save();

            $parentId = $translatedVariation->get_parent_id();

            if ($parentId) {
                WC_Product_Variable::sync($parentId);
                wc_delete_product_transients($parentId);
            }
        }
    }

    private static function syncVariationByParentAndPosition(int $variationId, WC_Product $variation): void
    {
        $sourceParentId = $variation->get_parent_id();

        if (! $sourceParentId) {
            return;
        }

        $parentTranslations = pll_get_post_translations($sourceParentId);

        if (empty($parentTranslations)) {
            return;
        }

        $sourceSiblingIds = self::getVariationIds($sourceParentId);

        $variationIndex = array_search($variationId, array_map('intval', $sourceSiblingIds), true);

        if ($variationIndex === false) {
            return;
        }

        foreach ($parentTranslations as $translatedParentId) {
            $translatedParentId = (int) $translatedParentId;

            if ($translatedParentId === $sourceParentId) {
                continue;
            }

            $translatedSiblingIds = self::getVariationIds($translatedParentId);

            if (empty($translatedSiblingIds[$variationIndex])) {
                continue;
            }

            $translatedVariation = wc_get_product((int) $translatedSiblingIds[$variationIndex]);

            if (! $translatedVariation || ! $translatedVariation->is_type('variation')) {
                continue;
            }

            self::copyPrices($variation, $translatedVariation);

            $translatedVariation->save();

            WC_Product_Variable::sync($translatedParentId);
            wc_delete_product_transients($translatedParentId);
        }

        WC_Product_Variable::sync($sourceParentId);
        wc_delete_product_transients($sourceParentId);
    }

    private static function copyVariablePricesByPosition(WC_Product $sourceProduct, WC_Product $targetProduct): void
    {
        $sourceVariationIds = self::getVariationIds($sourceProduct->get_id());
        $targetVariationIds = self::getVariationIds($targetProduct->get_id());

        foreach ($sourceVariationIds as $index => $sourceVariationId) {
            if (empty($targetVariationIds[$index])) {
                continue;
            }

            $sourceVariation = wc_get_product((int) $sourceVariationId);
            $targetVariation = wc_get_product((int) $targetVariationIds[$index]);

            if (
                ! $sourceVariation ||
                ! $targetVariation ||
                ! $sourceVariation->is_type('variation') ||
                ! $targetVariation->is_type('variation')
            ) {
                continue;
            }

            self::copyPrices($sourceVariation, $targetVariation);
            $targetVariation->save();
        }

        WC_Product_Variable::sync($targetProduct->get_id());
        wc_delete_product_transients($targetProduct->get_id());
    }

    private static function resyncVariableParents(int $productId): void
    {
        $translations = pll_get_post_translations($productId);

        if (empty($translations)) {
            return;
        }

        foreach ($translations as $translatedProductId) {
            $translatedProductId = (int) $translatedProductId;

            if (! $translatedProductId) {
                continue;
            }

            WC_Product_Variable::sync($translatedProductId);
            wc_delete_product_transients($translatedProductId);
        }
    }

    private static function copyPrices(WC_Product $from, WC_Product $to): void
    {
        $to->set_regular_price($from->get_regular_price());
        $to->set_sale_price($from->get_sale_price());
        $to->set_date_on_sale_from($from->get_date_on_sale_from());
        $to->set_date_on_sale_to($from->get_date_on_sale_to());
    }

    private static function getVariationIds(int $parentId): array
    {
        return wc_get_products([
            'type'    => 'variation',
            'parent'  => $parentId,
            'limit'   => -1,
            'return'  => 'ids',
            'orderby' => 'menu_order',
            'order'   => 'ASC',
        ]);
    }

    private static function canSync(): bool
    {
        return function_exists('pll_get_post_translations')
            && function_exists('wc_get_product')
            && function_exists('wc_get_products')
            && class_exists('WC_Product')
            && class_exists('WC_Product_Variable');
    }
}

add_action('plugins_loaded', static function () {
    if (function_exists('pll_get_post_translations')) {
        SyncWCPricesPolylang::init();
    }
});