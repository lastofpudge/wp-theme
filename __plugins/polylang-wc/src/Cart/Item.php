<?php

namespace WP_Syntex\Polylang_WC\Cart;

use PLL_Language;
use PLLWC_Data_Store;
use PLLWC_Product_Language_CPT;
use WP_Term;

/**
 * A class to manage the cart item.
 *
 * @since 2.2
 *
 * @phpstan-type CartItem array{
 *   product_id: int,
 *   variation_id: int,
 *   variation: array,
 *   data: \WC_Product,
 *   quantity: int,
 *   key: string,
 * }
 */
class Item
{
    /**
     * The cart item.
     *
     * @var array
     *
     * @phpstan-var CartItem
     */
    private $item;

    /**
     * The product language data store.
     *
     * @var PLLWC_Product_Language_CPT
     */
    private $data_store;

    /**
     * Whether the cart item is a variation.
     *
     * @var bool
     */
    private $is_variation = false;

    /**
     * Constructor.
     *
     * @since 2.2
     *
     * @param array $item The cart item array.
     *
     * @phpstan-param CartItem $item
     */
    public function __construct(array $item)
    {
        $this->item = $item;
        $this->data_store = PLLWC_Data_Store::load('product_language');
        $this->is_variation = !empty($item['variation_id']);
    }

    /**
     * Translates the cart item to the specified language.
     *
     * @since 2.2
     *
     * @param PLL_Language $lang The target language.
     *
     * @return array<string, mixed> The translated cart item array.
     */
    public function translate(PLL_Language $lang): array
    {
        $untranslated_items = $this->item;
        if (
            ($this->is_variation && !$this->translate_variation($lang))
            || !$this->translate_parent($lang)
        ) {
            return $untranslated_items;
        }

        /**
         * Filters a cart item when it is translated.
         *
         * @since 0.6
         *
         * @param array  $item Cart item.
         * @param string $lang Language code.
         *
         * @phpstan-param CartItem $item
         */
        $this->item = apply_filters('pllwc_translate_cart_item', $this->item, $lang->slug);

        /**
         * Filters the cart item data.
         * This filters aims to replace the filter 'woocommerce_add_cart_item_data',
         * which can't be used here as it conflicts with WooCommerce Bookings,
         * which uses the filter to create new bookings and not only to filter the cart item data.
         *
         * @since 0.7.4
         *
         * @param array $cart_item_data Cart item data.
         * @param array $item           Cart item.
         *
         * @phpstan-param CartItem $item
         */
        $cart_item_data = (array) apply_filters('pllwc_add_cart_item_data', [], $this->item);
        $this->item['key'] = WC()->cart->generate_cart_id($this->item['product_id'], $this->item['variation_id'], $this->item['variation'], $cart_item_data);

        return $this->item;
    }

    /**
     * Translates a variation cart item to the specified language.
     *
     * @since 2.2
     *
     * @param PLL_Language $lang The target language.
     *
     * @return bool Whether the variation was translated.
     */
    private function translate_variation(PLL_Language $lang): bool
    {
        /** @var PLL_Language|false $orig_lang */
        $orig_lang = $this->data_store->get_language($this->item['variation_id'], \OBJECT);

        if (!$orig_lang || $lang->slug === $orig_lang->slug) {
            return false;
        }

        $tr_id = $this->data_store->get($this->item['variation_id'], $lang);
        if (empty($tr_id)) {
            return false;
        }

        $tr_variation = wc_get_product($tr_id);
        if (!$tr_variation) {
            return false;
        }

        $this->item['variation_id'] = $tr_variation->get_id();
        $this->item['data'] = $tr_variation;
        $this->item['variation'] = $this->translate_attributes($this->item['variation'], $lang, $orig_lang);

        return true;
    }

    /**
     * Translates a simple product cart item to the specified language.
     *
     * @since 2.2
     *
     * @param PLL_Language $lang The target language.
     *
     * @return bool Whether the parent was translated.
     */
    private function translate_parent(PLL_Language $lang): bool
    {
        $tr_id = $this->data_store->get($this->item['product_id'], $lang);
        if (empty($tr_id) || $tr_id === $this->item['product_id']) {
            return false;
        }

        $tr_product = wc_get_product($tr_id);
        if (!$tr_product) {
            return false;
        }

        $this->item['product_id'] = $tr_product->get_id();
        $this->item['data'] = $tr_product;

        return true;
    }

    /**
     * Translates product variation attributes to the specified language.
     *
     * @since 2.2
     *
     * @param array<string, string> $attributes The variation attributes array.
     * @param PLL_Language          $lang       The target language.
     * @param PLL_Language          $orig_lang  The original language.
     *
     * @return array<string, string> The translated attributes array.
     */
    public function translate_attributes(array $attributes, PLL_Language $lang, PLL_Language $orig_lang): array
    {
        foreach ($attributes as $name => $value) {
            if ('' === $value) {
                continue;
            }

            $taxonomy = wc_attribute_taxonomy_name(str_replace('attribute_pa_', '', urldecode($name)));

            if (!taxonomy_exists($taxonomy)) {
                continue;
            }

            // Don't use get_term_by( 'slug' ) which is filtered in the current language by Polylang Pro.
            $terms = get_terms(['taxonomy' => $taxonomy, 'slug' => $value, 'lang' => $orig_lang->slug]);

            if (empty($terms) || !is_array($terms)) {
                continue;
            }

            $term = reset($terms);
            if (!$term instanceof WP_Term) {
                continue;
            }

            $term_id = pll_get_term($term->term_id, $lang);
            if (!$term_id) {
                continue;
            }

            $term = get_term($term_id, $taxonomy);
            if (!$term instanceof WP_Term) {
                continue;
            }

            $attributes[$name] = $term->slug;
        }

        return $attributes;
    }
}
