<?php

namespace App\Controllers;

use Timber\Timber;

class ProductController extends Controller
{
    /** @var array */
    protected array $data;

    public function __construct()
    {
        parent::__construct();

        $this->data = Timber::context();
    }

    public function index(): array
    {
        $this->data['product'] = wc_get_product(get_the_ID());
        $this->data['post'] = Timber::get_post();
        $this->data['categories'] = get_the_terms(get_the_ID(), 'product_cat');

        $related_limit = wc_get_loop_prop('columns');
        $related_ids = wc_get_related_products(get_the_ID(), $related_limit);
        $this->data['related_products'] = Timber::get_posts($related_ids);

        return $this->data;
    }

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

            $queryArgs = $_GET;

            unset(
                $queryArgs['paged'],
                $queryArgs['page'],
                $queryArgs['product-page'],
                $queryArgs['filter_'.$attributeName],
                $queryArgs['query_type_'.$attributeName]
            );

            $attributes[] = [
                'label' => $taxonomy->attribute_label,
                'terms' => array_map(function ($term) use ($attributeName, $queryArgs) {
                    $term->filter_url = add_query_arg(
                        array_merge(
                            $queryArgs,
                            ['filter_'.$attributeName => $term->slug]
                        ),
                        $this->getCurrentArchiveUrl()
                    );

                    return $term;
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
            $queryArgs['posts_per_archive_page']
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

    public function checkout(): array
    {
        $this->data['post'] = Timber::get_post();

        // Order received page
        $this->data['is_order_received'] = is_wc_endpoint_url('order-received');
        $this->data['order_id'] = null;
        $this->data['order'] = null;

        if ($this->data['is_order_received']) {
            $orderId = absint(get_query_var('order-received'));

            if ($orderId) {
                $order = wc_get_order($orderId);

                if ($order) {
                    $this->data['order_id'] = $order->get_id();
                    $this->data['order'] = $order;
                }
            }

            return $this->data;
        }

        $checkout = WC()->checkout();

        if (WC()->cart) {
            WC()->cart->calculate_shipping();
            WC()->cart->calculate_totals();
        }

        $this->data['checkout'] = $checkout;
        $this->data['fields'] = $checkout->get_checkout_fields();
        $this->data['checkout_url'] = get_localized_wc_page_url('checkout');
        $this->data['checkout_nonce'] = wp_create_nonce('woocommerce-process_checkout');
        $this->data['http_referer'] = esc_attr(wp_unslash($_SERVER['REQUEST_URI'] ?? '/'));

        /**
         * Shipping methods HTML.
         */
        ob_start();

        if (WC()->cart && WC()->cart->needs_shipping()) {
            wc_cart_totals_shipping_html();
        }

        $this->data['shipping_methods_html'] = ob_get_clean();

        /**
         * Payment gateways.
         */
        $gateways = [];

        foreach (WC()->payment_gateways()->get_available_payment_gateways() as $id => $gateway) {
            ob_start();
            $gateway->payment_fields();

            $gateways[$id] = [
                'id'          => $id,
                'title'       => $gateway->get_title(),
                'description' => $gateway->get_description(),
                'fields_html' => ob_get_clean(),
                'icon'        => $gateway->get_icon(),
            ];
        }

        $this->data['payment_gateways'] = $gateways;

        return $this->data;
    }

    public function cart(): array
    {
        $this->data['coupons_enabled'] = wc_coupons_enabled();

        return $this->data;
    }
}
