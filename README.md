# wp-theme

Lightweight WordPress starter theme built with [Timber 2](https://timber.github.io/docs/v2/), Twig and Vite. It keeps a familiar WordPress request lifecycle while providing controllers, Twig views, Carbon Fields examples and a small asset pipeline.

## Requirements

- WordPress 6.9 or later
- PHP 8.2–8.5
- Composer 2
- Node.js 18 or later

`wp-theme` is a theme, not a full WordPress distribution: WordPress core, its database and web-server configuration are supplied by the host project.

## Quick start

1. Place the repository at `wp-content/themes/wp-theme` in an existing WordPress project.
2. From the theme directory, install the locked PHP and JavaScript dependencies:

   ```bash
   composer install
   npm ci
   npm run build
   ```

   Composer installs the two declared WordPress plugins into `wp-content/plugins`. Activate only the plugins needed by the site in wp-admin. Yoast is optional: the theme calls its breadcrumbs only when the plugin is active. All-in-One WP Migration is a development/operations tool and does not need to be active on the public site.

3. In wp-admin, activate **wp-theme** in **Appearance → Themes**.
4. Create a menu and assign it to the `Main menu` location. Create pages, then select one of the supplied page templates where appropriate.

## Daily development

```bash
npm run dev       # rebuild assets while source files change
npm run lint      # ESLint + Stylelint
npm run fix       # fix lintable JS and SCSS issues
composer test     # format-check PHP with Pint
```

Vite outputs production files to `resources/assets/dist`. Commit or deploy those built files together with the theme; never deploy `node_modules`.

## Architecture

The theme uses WordPress conditionals for routing. `resources/index.php` loads `routes/base.php`; a matched route calls a controller and renders its Twig view through `Core\Route`.

| Path | Purpose |
| --- | --- |
| `routes/base.php` | Maps WordPress request types to controllers and views |
| `app/Controllers/` | Builds the data supplied to Twig templates |
| `resources/views/` | Twig layouts, components and page views |
| `resources/views/template-*.php` | WordPress page-template entry points |
| `app/CustomFields/` | Carbon Fields examples for pages, options and blocks |
| `app/Handlers/AjaxHandlers/` | Optional AJAX handlers used by the included examples |
| `resources/assets/src/` | SCSS, JavaScript and source images |
| `resources/assets/dist/` | Built frontend assets |

### Add a page view with a controller

Use a controller when a view needs queries, transformation, reusable view data or several related fields.

1. Add a method to `app/Controllers/PageController.php` and put its data in `$this->data`.
2. Add a Twig view under `resources/views/pages/`.
3. Create a `resources/views/template-*.php` page template that calls `Route::load()` with the controller method and Twig view.
4. Create a page in wp-admin and select that template.

### Add a shared Twig component

Create a partial in `resources/views/components/` and include it from a page or layout with Twig’s `{% include %}` tag. Keep data preparation in a controller or `timber/context` filter rather than querying WordPress from the view.

### Add custom fields

The existing files in `app/CustomFields/` are intentionally kept as working examples. Add a field group there and use Carbon Fields getters in the corresponding controller or Twig view. Carbon Fields is booted in `resources/functions.php`.

### Read Carbon Fields directly in Twig

For a small amount of presentation-only data, a controller is optional. Timber exposes WordPress and Carbon Fields functions through `fn()`, so a Twig view can read fields directly:

```twig
{# A simple field on the current page or post #}
{% set subtitle = fn('carbon_get_post_meta', post.ID, 'sub_title') %}
{% if subtitle %}
  <p class="subtitle">{{ subtitle }}</p>
{% endif %}

{# A theme option #}
<span>{{ fn('carbon_get_theme_option', 'site_name') }}</span>

{# A complex field #}
{% for item in fn('carbon_get_post_meta', post.ID, 'tech_items') %}
  <article>
    <h2>{{ item.tech_title }}</h2>
    {% for child in item.tech_sub_items %}
      <p>{{ child.tech_title }}</p>
    {% endfor %}
  </article>
{% endfor %}
```

Twig escapes output by default. Use `|raw` only for trusted rich-text content that an administrator entered, for example `{{ fn('carbon_get_post_meta', post.ID, 'new_short_text')|raw }}`. Keep data in a controller when its retrieval or preparation becomes non-trivial; this is about code clarity, not speed.

### Read native WordPress post data in Twig

`get_post_meta()` and `get_post_field()` are standard WordPress APIs and are appropriate in Twig through `fn()`. There is no WordPress function named `post_get_meta()`.

```twig
{# Prefer Timber's object API for the usual post properties #}
<h1>{{ post.title }}</h1>

{# A native custom meta value; true returns a single value #}
{% set badge = fn('get_post_meta', post.ID, 'badge', true) %}
{% if badge %}
  <span class="badge">{{ badge }}</span>
{% endif %}

{# A column from wp_posts, not post meta #}
{% set excerpt = fn('get_post_field', 'post_excerpt', post.ID) %}
{% if excerpt %}
  <p>{{ excerpt }}</p>
{% endif %}
```

Use `get_post_field()` for core `wp_posts` columns such as `post_title`, `post_excerpt` and `post_date`; use `get_post_meta()` for simple custom metadata. For Carbon Fields, prefer `carbon_get_post_meta()` — especially for complex, association, image and rich-text fields — because it returns the field value in the format Carbon Fields expects.

## WooCommerce

The full WooCommerce implementation, including PHP overrides and Twig storefront views, is maintained in the [`feature/woocommerce-integration`](https://github.com/lastofpudge/wp-theme/tree/feature/woocommerce-integration) branch. Use that branch for a store project; this default branch remains a framework-agnostic starter.

## Production deployment

Build and install dependencies before deployment:

```bash
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader
npm ci
npm run build
```

Deploy the theme including `vendor/` and `resources/assets/dist/`; exclude `node_modules/`. Production must use a PHP version in the declared range and be smoke-tested on its target runtime.

## Positioning

`wp-theme` is a focused alternative for teams that want Timber + Twig + Vite without adopting a full Laravel/Acorn stack. It favors conventional WordPress routing, readable project structure and optional integrations over a large framework surface.

## Useful commands

```bash
composer validate --strict --no-check-publish
composer audit --locked
composer test
npm run lint
npm run build
```
