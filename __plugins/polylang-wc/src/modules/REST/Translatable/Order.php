<?php

/**
 * @package Polylang-WC
 */

namespace WP_Syntex\Polylang_WC\REST\Translatable;

use WP_Post;
use WC_Order;
use PLL_REST_API;
use PLLWC_Data_Store;
use PLLWC_Order_Language_CPT;
use WP_Syntex\Polylang_Pro\REST\Translatable\Abstract_Object;

/**
 * Exposes the product language and translations in the REST API.
 *
 * @since 2.2
 */
class Order extends Abstract_Object
{
    /**
     * Order language data store.
     *
     * @var PLLWC_Order_Language_CPT
     */
    private $data_store;

    /**
     * Constructor.
     *
     * @since 2.2
     *
     * @param PLL_REST_API $rest_api  Instance of `PLL_REST_API`.
     */
    public function __construct(PLL_REST_API $rest_api)
    {
        parent::__construct($rest_api, array( 'shop_order' ));

        $this->data_store = PLLWC_Data_Store::load('order_language');
    }

    /**
     * Returns the object language.
     *
     * @since 2.2
     *
     * @param array $object Order array.
     * @return string|false
     */
    public function get_language($object)
    {
        return $this->data_store->get_language($object['id']);
    }

    /**
     * Sets the object language.
     *
     * @since 2.2
     *
     * @param string   $lang   Language code.
     * @param WC_Order $object Instance of WC_Order.
     * @return bool True when successfully assigned. False otherwise.
     */
    public function set_language($lang, $object)
    {
        if ($object instanceof WC_Order) {
            return $this->data_store->set_language($object->get_id(), $lang);
        }

        return parent::set_language($lang, $object);
    }

    /**
     * Returns the current object type, e.g. 'post' or 'term'.
     * The methods will never be called for this object type since `self::set_language()` and `self::get_language()` are overridden.
     *
     * @since 2.2
     *
     * @return string
     */
    protected function get_type(): string
    {
        return 'post';
    }

    /**
     * Returns the REST identifier for the item.
     *
     * @since 3.8
     *
     * @param array|object $item Item array or object, usually a post or term.
     * @return int The REST identifier, 0 if not found.
     */
    protected function get_db_id($item): int
    {
        if (is_array($item)) {
            return $item['ID'] ?? 0;
        }

        // `WC_Order` for `wc/v3/orders/{id}` update callback.
        if ($item instanceof WC_Order) {
            return $item->get_id();
        }

        // `WP_Post` for `wp/v2/order/{id}` update callback.
        if ($item instanceof WP_Post) {
            return $item->ID;
        }

        return 0;
    }
}
