<?php

namespace App\Services;

class ProductFilterQuery
{
    public function __construct(private readonly ?\WP_Query $baseQuery)
    {
    }

    public function getProductIds(array $excludedTaxonomies = [], bool $withoutPrice = false): array
    {
        if ($this->baseQuery === null) {
            return [];
        }

        $queryArgs                           = $this->baseQuery->query_vars;
        $queryArgs['fields']                 = 'ids';
        $queryArgs['nopaging']               = true;
        $queryArgs['no_found_rows']          = true;
        $queryArgs['cache_results']          = false;
        $queryArgs['suppress_filters']       = false;
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
            $queryArgs['lang']
        );

        return array_map('intval', (new \WP_Query($queryArgs))->posts);
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
}
