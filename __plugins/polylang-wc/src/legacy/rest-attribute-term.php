<?php

/**
 * @package Polylang-WC
 */

/**
 * Exposes the term language in the REST API for the product attributes.
 * Used for backward compatibility with Polylang < 3.8.
 *
 * @since 2.2
 */
class PLLWC_REST_Attribute_Term extends PLL_REST_Term
{
    /**
     * Constructor.
     *
     * @since 2.2
     *
     * @param PLL_REST_API $rest_api  Instance of `PLL_REST_API`.
     */
    public function __construct(PLL_REST_API $rest_api)
    {
        parent::__construct($rest_api, array( 'product_attribute_term' => array( 'filters' => false ) ));
    }

    /**
     * Returns the REST field type for a content type.
     *
     * @since 2.2
     *
     * @param string $type Taxonomy name.
     * @return string REST API field type.
     */
    protected function get_rest_field_type($type)
    {
        if (strpos($type, 'pa_') !== 0) {
            return $type;
        }

        foreach (wc_get_attribute_taxonomies() as $tax) {
            if (wc_attribute_taxonomy_name($tax->attribute_name) === $type) {
                return 'product_attribute_term';
            }
        }

        return $type;
    }
}
