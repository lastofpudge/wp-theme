# wp-theme

WordPress theme built on [Timber 2.x](https://timber.github.io/docs/) (Twig templating) with a Laravel-style MVC pattern, full WooCommerce integration, and Polylang multilingual support.

## Requirements

- PHP 8.2+
- Node.js 18+
- Composer
- WooCommerce
- Polylang Pro

## Installation

```bash
composer install
npm install
npm run build
```

## Development

```bash
npm run dev    # Watch mode (Vite)
npm run build  # Production build
npm run lint   # ESLint + Stylelint
npm run fix    # Auto-fix lint issues
```

## PHP Tools

```bash
composer pint   # PSR-12 code style fixer
composer test   # Run all checks
```

## Architecture

Routing (`routes/base.php`) uses WordPress conditionals to dispatch to controllers via `Route::load(Controller::class, 'method', 'twig/view')`. The controller builds a `$data` array and renders a Twig template; `Route::renderView()` calls `exit` to prevent WordPress from loading its own template — this is intentional.

| Path                         | Purpose                                                     |
| ---------------------------- | ----------------------------------------------------------- |
| `app/Controllers/`           | Page controllers — each maps to a route + Twig view         |
| `app/Admin/AdminOptions.php` | Theme bootstrap: hooks, assets, Timber context              |
| `app/Extensions/`            | Twig WC helpers (`wc_price`, `wc_product`, etc.)            |
| `app/Handlers/AjaxHandlers/` | WP AJAX handlers (cart, login, contact)                     |
| `app/helpers.php`            | Global helpers: `get_cart_data()`, `capture_action()`       |
| `resources/views/`           | All Twig templates (`layouts/base.twig` is the root layout) |
| `resources/assets/src/`      | Vanilla JS + SCSS source — no jQuery                        |
| `woocommerce/`               | WC PHP template overrides (bridge to Twig)                  |

Every Twig template automatically receives `cart`, `languages`, `currency_symbol`, `cart_link`, `checkout_link`, `account_link`, and `main_menu` from `AdminOptions::registerContext()`. Frontend scripts use a global `data` object (`ajax_url`, `nonce`, `price_slider`) from `AdminOptions::registerScripts()`.

## Links

- [Timber](https://github.com/timber/timber)
- [Carbon Fields](https://github.com/htmlburger/carbon-fields)
- [Vite](https://vite.dev/)
