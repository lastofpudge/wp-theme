<?php

namespace App\Controllers;

use App\Services\ProductFilterQuery;
use Timber\Timber;

class ShopController extends Controller
{
    public function archive(): array
    {
        global $wp_query;

        $posts = Timber::get_posts();
        $this->data['posts'] = $posts;
        $filterArgs = array_filter(
            $_GET,
            fn ($k) => !in_array($k, ['paged', 'page', 'product-page'], true),
            ARRAY_FILTER_USE_KEY
        );
        $this->data['pagination'] = $posts->pagination([
            'total'    => (int) ($wp_query->max_num_pages ?? 1),
            'add_args' => $filterArgs,
        ]);
        $cats = $this->getArchiveCategories();
        $this->data['categories'] = $cats['terms'];
        $this->data['categories_reset_url'] = $cats['reset_url'];
        $this->data['active_category'] = $cats['has_active'];
        $this->data['attributes'] = $this->getArchiveAttributes();

        $requestedMin = get_requested_price('min_price');
        $requestedMax = get_requested_price('max_price');
        $priceRange = $this->getPriceRange();
        $priceAbsMin = floor($priceRange['min']);
        $priceAbsMax = ceil($priceRange['max']);
        $priceMin = $requestedMin ?? $priceAbsMin;
        $priceMax = $requestedMax ?? $priceAbsMax;

        if ($priceMin > $priceMax) {
            [$priceMin, $priceMax] = [$priceMax, $priceMin];
        }

        $this->data['price_abs_min'] = $priceAbsMin;
        $this->data['price_abs_max'] = $priceAbsMax;
        $this->data['price_min'] = max($priceAbsMin, min($priceMin, $priceAbsMax));
        $this->data['price_max'] = max($this->data['price_min'], min($priceMax, $priceAbsMax));

        $activeFilterKeys = array_filter(
            array_keys($_GET),
            fn ($k) => str_starts_with($k, 'filter_') || str_starts_with($k, 'query_type_')
        );
        $this->data['has_active_filters'] = !empty($activeFilterKeys) || $requestedMin !== null || $requestedMax !== null;
        $this->data['reset_url'] = $this->getCurrentArchiveUrl();

        return $this->data;
    }

    private function getArchiveCategories(): array
    {
        if (function_exists('is_product_category') && is_product_category()) {
            return ['reset_url' => $this->getCurrentArchiveUrl(), 'has_active' => false, 'terms' => []];
        }

        global $wp_query;
        $filterQuery = new ProductFilterQuery($wp_query instanceof \WP_Query ? $wp_query : null);
        $productIds = $filterQuery->getProductIds(['product_cat'], true);
        $facetedTerms = array_values(array_filter(
            $this->getAttributeTermsForProducts('product_cat', $productIds),
            fn (\WP_Term $t) => $t->parent === 0
        ));

        $activeSlug = sanitize_text_field(wp_unslash($_GET['filter_product_cat'] ?? ''));
        $currentUrl = $this->getCurrentArchiveUrl();

        $queryArgs = array_map(
            fn ($v) => is_array($v) ? array_map('sanitize_text_field', $v) : sanitize_text_field((string) $v),
            $_GET
        );
        unset($queryArgs['paged'], $queryArgs['page'], $queryArgs['product-page'], $queryArgs['filter_product_cat']);

        $resetUrl = add_query_arg($queryArgs, $currentUrl);

        if ($facetedTerms === []) {
            return ['reset_url' => $resetUrl, 'has_active' => false, 'terms' => []];
        }

        $termData = array_map(function (\WP_Term $term) use ($activeSlug, $queryArgs, $currentUrl, $resetUrl) {
            $isActive = $activeSlug === $term->slug;
            $filterUrl = $isActive
                ? $resetUrl
                : add_query_arg(array_merge($queryArgs, ['filter_product_cat' => $term->slug]), $currentUrl);

            return [
                'name'       => $term->name,
                'slug'       => $term->slug,
                'count'      => $term->count,
                'is_active'  => $isActive,
                'filter_url' => $filterUrl,
            ];
        }, $facetedTerms);

        return ['reset_url' => $resetUrl, 'has_active' => $activeSlug !== '', 'terms' => $termData];
    }

    private function getPriceRange(): array
    {
        global $wpdb;
        $row = $wpdb->get_row(
            "SELECT MIN(CAST(meta_value AS DECIMAL(10,2))) AS min_price,
                    MAX(CAST(meta_value AS DECIMAL(10,2))) AS max_price
             FROM {$wpdb->postmeta}
             WHERE meta_key = '_price' AND meta_value != ''"
        );

        return [
            'min' => (float) ($row->min_price ?? 0),
            'max' => (float) ($row->max_price ?? 1000000),
        ];
    }

    private function getArchiveAttributes(): array
    {
        global $wp_query;
        $filterQuery = new ProductFilterQuery($wp_query instanceof \WP_Query ? $wp_query : null);
        $attributes = [];

        foreach (wc_get_attribute_taxonomies() as $taxonomy) {
            $attributeName = $taxonomy->attribute_name;
            $attributeTaxonomy = 'pa_'.$attributeName;
            $productIds = $filterQuery->getProductIds([$attributeTaxonomy], true);
            $terms = $this->getAttributeTermsForProducts($attributeTaxonomy, $productIds);

            if ($terms === []) {
                continue;
            }

            $queryArgs = array_map(
                fn ($v) => is_array($v) ? array_map('sanitize_text_field', $v) : sanitize_text_field((string) $v),
                $_GET
            );

            $currentFilter = $queryArgs['filter_'.$attributeName] ?? '';

            unset(
                $queryArgs['paged'],
                $queryArgs['page'],
                $queryArgs['product-page'],
                $queryArgs['filter_'.$attributeName],
                $queryArgs['query_type_'.$attributeName]
            );

            $resetUrl = add_query_arg($queryArgs, $this->getCurrentArchiveUrl());

            $attributes[] = [
                'label'     => $taxonomy->attribute_label,
                'param'     => 'filter_'.$attributeName,
                'reset_url' => $resetUrl,
                'terms'     => array_map(
                    fn ($term) => $this->buildTermData($term, $attributeName, $queryArgs, $currentFilter, $resetUrl),
                    $terms
                ),
            ];
        }

        return $attributes;
    }

    private function buildTermData(\WP_Term $term, string $attributeName, array $queryArgs, string $currentFilter, string $resetUrl): array
    {
        $isActive = $currentFilter !== '' && $currentFilter === $term->slug;
        $filterUrl = $isActive
            ? $resetUrl
            : add_query_arg(
                array_merge($queryArgs, ['filter_'.$attributeName => $term->slug]),
                $this->getCurrentArchiveUrl()
            );

        return [
            'name'       => $term->name,
            'slug'       => $term->slug,
            'count'      => $term->count,
            'is_active'  => $isActive,
            'filter_url' => $filterUrl,
        ];
    }

    private function getAttributeTermsForProducts(string $taxonomy, array $productIds): array
    {
        if ($productIds === []) {
            return [];
        }

        $terms = wp_get_object_terms($productIds, $taxonomy, [
            'fields'  => 'all_with_object_id',
            'orderby' => 'name',
            'order'   => 'ASC',
        ]);

        if (is_wp_error($terms) || $terms === []) {
            return [];
        }

        $groupedTerms = [];
        $termProductMap = [];

        foreach ($terms as $term) {
            $termId = (int) $term->term_id;
            $objectId = (int) $term->object_id;

            if (!isset($groupedTerms[$termId])) {
                $term->count = 0;
                $groupedTerms[$termId] = $term;
                $termProductMap[$termId] = [];
            }

            if (isset($termProductMap[$termId][$objectId])) {
                continue;
            }

            $termProductMap[$termId][$objectId] = true;
            $groupedTerms[$termId]->count++;
        }

        return array_values($groupedTerms);
    }

    private function getCurrentArchiveUrl(): string
    {
        $requestUri = (string) wp_unslash($_SERVER['REQUEST_URI'] ?? '/');
        $path = strtok($requestUri, '?') ?: '/';
        $path = (string) preg_replace('#/page/\d+/?$#', '/', $path);

        return home_url($path);
    }
}
