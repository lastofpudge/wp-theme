<?php

namespace App\Controllers;

use Timber\Timber;

class ShopController extends Controller
{
    public function category(): array
    {
        $this->data['category'] = get_term(get_queried_object()->term_id, 'product_cat');

        return $this->data;
    }

    public function archive(): array
    {
        $this->data['posts'] = Timber::get_posts();
        $this->data['attributes'] = $this->getArchiveAttributes();

        $requestedMin = get_requested_price('min_price');
        $requestedMax = get_requested_price('max_price');
        $priceAbsMin = 0.0;
        $priceAbsMax = 1000000.0;
        $priceMin = $requestedMin ?? $priceAbsMin;
        $priceMax = $requestedMax ?? $priceAbsMax;

        if ($priceMin > $priceMax) {
            [$priceMin, $priceMax] = [$priceMax, $priceMin];
        }

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

    private function getArchiveAttributes(): array
    {
        $attributes = [];

        foreach (wc_get_attribute_taxonomies() as $taxonomy) {
            $attributeName = $taxonomy->attribute_name;
            $attributeTaxonomy = 'pa_'.$attributeName;
            $productIds = $this->getArchiveProductIds([$attributeTaxonomy], true);
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
                'terms'     => array_map(function ($term) use ($attributeName, $queryArgs, $currentFilter, $resetUrl) {
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
                }, $terms),
            ];
        }

        return $attributes;
    }

    private function getArchiveProductIds(array $excludedTaxonomies = [], bool $withoutPrice = false): array
    {
        global $wp_query;

        if (!($wp_query instanceof \WP_Query)) {
            return [];
        }

        $queryArgs = $wp_query->query_vars;
        $queryArgs['fields'] = 'ids';
        $queryArgs['nopaging'] = true;
        $queryArgs['no_found_rows'] = true;
        $queryArgs['cache_results'] = false;
        $queryArgs['suppress_filters'] = false;
        $queryArgs['update_post_meta_cache'] = false;
        $queryArgs['update_post_term_cache'] = false;

        if ($withoutPrice) {
            $queryArgs['meta_query'] = $this->removePriceFilter((array) ($queryArgs['meta_query'] ?? []));
        }

        if ($excludedTaxonomies !== []) {
            $queryArgs['tax_query'] = $this->removeAttributeFilters(
                (array) ($queryArgs['tax_query'] ?? []),
                $excludedTaxonomies
            );

            if (isset($queryArgs['taxonomy']) && in_array($queryArgs['taxonomy'], $excludedTaxonomies, true)) {
                unset($queryArgs['taxonomy'], $queryArgs['term'], $queryArgs['term_id']);
            }

            foreach ($excludedTaxonomies as $taxonomy) {
                if (str_starts_with($taxonomy, 'pa_')) {
                    unset($queryArgs['filter_'.substr($taxonomy, 3)], $queryArgs['query_type_'.substr($taxonomy, 3)]);
                }
            }
        }

        unset(
            $queryArgs['offset'],
            $queryArgs['paged'],
            $queryArgs['page'],
            $queryArgs['posts_per_page'],
            $queryArgs['posts_per_archive_page'],
            $queryArgs['lang'] // count terms across all languages
        );

        $productsQuery = new \WP_Query($queryArgs);

        return array_map('intval', $productsQuery->posts);
    }

    private function removePriceFilter(array $metaQuery): array
    {
        $filtered = [];

        foreach ($metaQuery as $key => $clause) {
            if ($key === 'relation' || !is_array($clause) || ($clause['key'] ?? null) !== '_price') {
                $filtered[$key] = $clause;
            }
        }

        return $filtered;
    }

    private function removeAttributeFilters(array $taxQuery, array $excludedTaxonomies): array
    {
        $filteredTaxQuery = [];

        foreach ($taxQuery as $key => $clause) {
            if ($key === 'relation') {
                $filteredTaxQuery[$key] = $clause;
                continue;
            }

            if (!is_array($clause)) {
                $filteredTaxQuery[] = $clause;
                continue;
            }

            if (isset($clause['taxonomy']) && in_array($clause['taxonomy'], $excludedTaxonomies, true)) {
                continue;
            }

            if (isset($clause['relation'])) {
                $nested = $this->removeAttributeFilters($clause, $excludedTaxonomies);

                if (count($nested) > 1) {
                    $filteredTaxQuery[] = $nested;
                }

                continue;
            }

            $filteredTaxQuery[] = $clause;
        }

        return $filteredTaxQuery;
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
        $path = strtok($requestUri, '?');

        return home_url($path ?: '/');
    }
}
