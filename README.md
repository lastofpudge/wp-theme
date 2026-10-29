# wp-theme — WooCommerce edition

WooCommerce-focused WordPress starter theme built with [Timber 2](https://timber.github.io/docs/v2/), Twig and Vite. It provides storefront, product, cart, checkout, account and email templates, plus Polylang-aware context and vanilla-JavaScript cart interactions.

## Requirements

- WordPress 6.9 or later
- PHP 8.2–8.5
- Composer 2
- Node.js 18 or later
- Active WooCommerce and Polylang Pro plugins

`wp-theme` is a theme, not a full WordPress distribution. WordPress core, its database, WooCommerce and Polylang Pro are supplied and activated by the host project.

## Quick start

1. Place the repository at `wp-content/themes/wp-theme` in an existing WordPress project.
2. Install and activate WooCommerce and Polylang Pro before activating the theme.
3. From the theme directory, install the locked dependencies and build assets:

```bash
composer install
npm ci
npm run build
```

Composer installs the declared WordPress plugins into `wp-content/plugins`. Activate only the plugins needed by the site. Yoast breadcrumbs are used only when Yoast is active; All-in-One WP Migration is a development/operations tool and does not need to run on the public site.

4. Activate **wp-theme** in **Appearance → Themes**.
5. Complete WooCommerce and Polylang setup, create the main navigation menu and assign it to the `Main menu` location.

## Development

```bash
npm run dev    # Watch mode (Vite)
npm run build  # Production build
npm run lint   # ESLint + Stylelint
npm run fix    # Auto-fix lint issues
```

`resources/assets/dist` contains production assets. Commit or deploy it with the theme; do not deploy `node_modules`.

## PHP tools

```bash
composer pint   # PSR-12 code style fixer
composer test   # Pint check; does not modify files
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

### Add a page view with a controller

Use a controller when a view needs WooCommerce queries, transformations or reusable view data.

1. Add a method to the appropriate controller in `app/Controllers/`.
2. Add a Twig view under `resources/views/`.
3. Map the matching WordPress conditional in `routes/base.php` to `Route::load()`.

### Read Carbon Fields directly in Twig

For small presentation-only values, a controller is optional. Timber exposes Carbon Fields functions through `fn()`:

```twig
{% set subtitle = fn('carbon_get_post_meta', post.ID, 'sub_title') %}
{% if subtitle %}
  <p class="subtitle">{{ subtitle }}</p>
{% endif %}

<span>{{ fn('carbon_get_theme_option', 'site_name') }}</span>

{% for item in fn('carbon_get_post_meta', post.ID, 'tech_items') %}
  <article>
    <h2>{{ item.tech_title }}</h2>
  </article>
{% endfor %}
```

Twig escapes output by default. Use `|raw` only for trusted administrator-authored rich text, for example `{{ fn('carbon_get_post_meta', post.ID, 'new_short_text')|raw }}`. Prefer `carbon_get_post_meta()` for Carbon Fields, especially complex, image, association and rich-text values.

### Read native WordPress post data in Twig

`get_post_meta()` and `get_post_field()` are standard WordPress APIs available through `fn()`; there is no `post_get_meta()` function.

```twig
<h1>{{ post.title }}</h1>

{% set badge = fn('get_post_meta', post.ID, 'badge', true) %}
{% if badge %}<span class="badge">{{ badge }}</span>{% endif %}

{% set excerpt = fn('get_post_field', 'post_excerpt', post.ID) %}
{% if excerpt %}<p>{{ excerpt }}</p>{% endif %}
```

Use `get_post_field()` for core `wp_posts` columns and `get_post_meta()` for simple custom metadata. Keep non-trivial data preparation in controllers for clarity, not for speed.

## Production deployment

```bash
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader
npm ci
npm run build
```

Deploy the theme including `vendor/` and `resources/assets/dist/`; exclude `node_modules/`. Test the storefront, product, cart, checkout, account and transactional emails in the target PHP/WordPress/WooCommerce environment before release.

## Positioning

This is the specialized WooCommerce edition of `wp-theme`: use it for stores that need Twig-based control over the complete storefront. The `master` branch remains the lighter, framework-agnostic WordPress starter.

## Links

- [Timber 2 documentation](https://timber.github.io/docs/v2/)
- [Carbon Fields](https://github.com/htmlburger/carbon-fields)
- [Vite](https://vite.dev/)
