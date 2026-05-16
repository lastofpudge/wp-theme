<?php

use Timber\Timber;

if (!function_exists('send_email')) {
    /**
     * Send a custom email.
     *
     * @param string $templateFilename
     * @param array  $templateData
     *
     * @return bool|null
     */
    function send_email(string $templateFilename, array $templateData): ?bool
    {
        $emailBody = compile_email_template($templateFilename, $templateData);
        $replyTo   = $templateData['reply_to'] ?? '';
        $isSent    = dispatch_email($templateData['subject'], $emailBody, $replyTo);

        return $isSent ?: null;
    }

    /**
     * Compile an email template.
     *
     * @param string $filename
     * @param array  $data
     *
     * @return string
     */
    function compile_email_template(string $filename, array $data): string
    {
        return Timber::compile('/resources/views/emails/'.$filename.'.twig', $data);
    }

    /**
     * Dispatch an email.
     *
     * @param string $subject
     * @param string $body
     *
     * @return bool
     */
    function dispatch_email(string $subject, string $body, string $replyTo = ''): bool
    {
        $adminEmail = get_option('admin_email');
        $headers    = ['Content-type: text/html; charset=utf-8', 'From: ' . $adminEmail];

        if ($replyTo !== '') {
            $headers[] = 'Reply-To: ' . $replyTo;
        }

        return wp_mail($adminEmail, $subject, $body, $headers);
    }
}

if (!function_exists('verify_ajax_nonce')) {
    function verify_ajax_nonce(): void
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'ajax-nonce')) {
            wp_send_json(['type' => 'error', 'message' => 'nonce_error']);
        }
    }
}

if (!function_exists('add_ajax_action')) {
    /**
     * Registers an AJAX action with WordPress.
     *
     * @param string $name The name of the AJAX action.
     */
    function add_ajax_action(string $name): void
    {
        $action_path = APP_PATH.'/Handlers/AjaxHandlers/'.$name.'.php';
        add_ajax_action_impl($name, 'wp_ajax', $action_path);
        add_ajax_action_impl($name, 'wp_ajax_nopriv', $action_path);
    }

    /**
     * Helper function to implement AJAX action registration.
     *
     * @param string $name        The name of the AJAX action.
     * @param string $hook        The WordPress hook to associate with the action.
     * @param string $action_path Path to the PHP file that handles the action.
     */
    function add_ajax_action_impl(string $name, string $hook, string $action_path): void
    {
        add_action($hook."_$name", function () use ($action_path) {
            require $action_path;
        });
    }
}

if (!function_exists('dd')) {
    /**
     * Debug function to dump and die. Outputs the given variable and stops execution.
     *
     * @param mixed $result The variable to be dumped.
     */
    function dd(mixed $result): void
    {
        echo '<pre>';
        print_r($result);
        exit;
    }
}

if (!function_exists('crb_get_i18n_suffix')) {
    /**
     * Get the suffix for internationalization, typically a language code.
     *
     * @return string The suffix for the current language, or an empty string if not set.
     */
    function crb_get_i18n_suffix(): string
    {
        $suffix = '';
        if (!defined('ICL_LANGUAGE_CODE')) {
            return $suffix;
        }

        return '_'.ICL_LANGUAGE_CODE;
    }
}

if (!function_exists('crb_get_i18n_theme_option')) {
    /**
     * Retrieves a theme option value with internationalization support.
     *
     * @param string $option_name The name of the theme option.
     *
     * @return mixed The value of the theme option for the current language.
     */
    function crb_get_i18n_theme_option(string $option_name): mixed
    {
        $suffix = crb_get_i18n_suffix();

        return carbon_get_theme_option($option_name.$suffix);
    }
}

if (!function_exists('get_cart_data')) {
    function get_cart_item_data(string $cart_item_key, array $cart_item): array
    {
        $_product     = $cart_item['data'];
        $productId    = (int) $cart_item['product_id'];
        $productPrice = apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key);
        $subTotal     = WC()->cart->get_product_subtotal($_product, $cart_item['quantity']);

        return [
            'id'                 => $productId,
            'name'               => $_product->get_name(),
            'link'               => get_permalink($productId),
            'thumbnail'          => $_product->get_image(),
            'quantity'           => (int) $cart_item['quantity'],
            'cart_item_key'      => $cart_item_key,
            'price_html'         => $productPrice,
            'line_subtotal_html' => apply_filters('woocommerce_cart_item_subtotal', $subTotal, $cart_item, $cart_item_key),
        ];
    }

    function get_cart_data(): array
    {
        $cart      = WC()->cart->get_cart();
        $cart_data = [];

        foreach ($cart as $cart_item_key => $cart_item) {
            $cart_data[] = get_cart_item_data($cart_item_key, $cart_item);
        }

        return $cart_data;
    }
}

if (!function_exists('get_cart_items_map')) {
    function get_cart_items_map(): array
    {
        $items = [];

        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
            $items[$cart_item_key] = get_cart_item_data($cart_item_key, $cart_item);
        }

        return $items;
    }
}

if (!function_exists('get_cart_coupon_list_html')) {
    function get_cart_coupon_list_html(): string
    {
        if (!wc_coupons_enabled()) {
            return '';
        }

        $coupons = WC()->cart->get_applied_coupons();
        if (empty($coupons)) {
            return '';
        }

        ob_start();
        ?>
        <p><?php esc_html_e('Active coupons:', 'woocommerce'); ?></p>
        <ul>
            <?php foreach ($coupons as $coupon) : ?>
                <li>
                    <span><?php echo esc_html($coupon); ?></span>
                    <button class="btn btn-danger js-remove-coupon" data-coupon="<?php echo esc_attr($coupon); ?>" type="button">
                        <i class="fa-solid fa-circle-xmark">x</i>
                    </button>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php

        return trim((string) ob_get_clean());
    }
}

if (!function_exists('get_cart_discount_row_html')) {
    function get_cart_discount_total_amount(): float
    {
        $cart = WC()->cart;

        $discount = method_exists($cart, 'get_discount_total')
            ? (float) $cart->get_discount_total()
            : 0.0;

        $discountTax = method_exists($cart, 'get_discount_tax')
            ? (float) $cart->get_discount_tax()
            : 0.0;

        return $discount + $discountTax;
    }

    function get_cart_discount_row_html(): string
    {
        $discountTotal = get_cart_discount_total_amount();
        if ($discountTotal <= 0) {
            return '';
        }

        ob_start();
        ?>
        <tr class="js-cart-discount-row text-success">
            <td colspan="4" class="text-end">
                <strong><?php esc_html_e('Discount', 'woocommerce'); ?></strong>
            </td>
            <td class="text-end">-<?php echo wp_kses_post(wc_price($discountTotal)); ?></td>
        </tr>
        <?php

        return trim((string) ob_get_clean());
    }
}

if (!function_exists('get_cart_summary_payload')) {
    function get_cart_summary_payload(): array
    {
        $count = WC()->cart->get_cart_contents_count();

        return [
            'cart'            => get_cart_data(),
            'items'           => get_cart_items_map(),
            'count'           => $count,
            'countLabel'      => _n('item', 'items', $count, 'woocommerce'),
            'subTotal'        => WC()->cart->get_cart_subtotal(),
            'total'           => WC()->cart->get_total(),
            'couponListHtml'  => get_cart_coupon_list_html(),
            'discountRowHtml' => get_cart_discount_row_html(),
        ];
    }
}

if (!function_exists('get_requested_price')) {
    function get_requested_price(string $key): ?float
    {
        if (!isset($_GET[$key])) {
            return null;
        }

        $value = trim((string) wp_unslash($_GET[$key]));

        if ($value === '') {
            return null;
        }

        return (float) $value;
    }
}

if (!function_exists('capture_output')) {
    function capture_output(callable $fn, mixed ...$args): string
    {
        ob_start();
        $fn(...$args);
        return (string) ob_get_clean();
    }
}

if (!function_exists('capture_action')) {
    function capture_action(string $hook, mixed ...$args): string
    {
        return capture_output(fn () => do_action($hook, ...$args));
    }
}
