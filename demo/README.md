# AgriExpress — Laravel Blade Front-End

This package contains the full AgriExpress UI rebuilt as Laravel Blade views, with
a shared layout, reusable components, controllers with sample data, named
routes, and one vanilla-JS file that powers every interactive piece (nav
dropdowns, mobile menu, hero/testimonial carousels, quantity steppers,
product gallery, tabs, swatches, coupon form, login/register tabs).

## Where things go

Copy each folder into the matching folder of an existing Laravel app
(`laravel new shopease` first if you don't have one):

```
app/Http/Controllers/*.php   → app/Http/Controllers/
resources/views/**           → resources/views/
routes/web.php                → routes/web.php (merge with your own)
public/js/app.js              → public/js/app.js
```

## Structure

```
resources/views/
  layouts/app.blade.php        Master layout: head, topbar, navbar, footer, scripts
  partials/
    topbar.blade.php           Slim announcement bar
    navbar.blade.php           Logo, nav links, dropdowns, search, mobile menu
    footer.blade.php           Newsletter band + footer columns
  components/
    product-card.blade.php     <x-product-card :product="$product" />
    star-rating.blade.php      <x-star-rating :rating="4.5" :count="24" />
    page-banner.blade.php      Shared inner-page banner (About/Blog/Contact)
  home.blade.php                Hero carousel, categories, new arrivals, promo
                                 banners, best sellers, testimonial carousel
  shop.blade.php                Filters sidebar + product grid + pagination
  product.blade.php             Gallery, swatches, qty, tabs, related products
  cart.blade.php                Cart rows, live totals, coupon form
  checkout.blade.php            Address selection + order summary
  account.blade.php             Dashboard, stats, recent orders table
  order-details.blade.php       Status tracker, items, address, payment
  about.blade.php               Story banner, value props, mission + stats,
                                 "what makes us different" panel
  blog.blade.php                Category pills, post grid, pagination,
                                 inline newsletter banner
  blog-show.blade.php           Single post + related posts
  contact.blade.php             Message form, contact channels, socials,
                                 embedded map, support banner
  auth/login.blade.php          Login / Register (single page, tab switch)
  errors/404.blade.php          Not found page (Laravel renders this
                                 automatically for 404 responses)
```

## Routes (routes/web.php)

All views are wired to named routes so `route('shop')`, `route('product', $id)`,
etc. resolve correctly:

| Route name            | URI                      | Controller                     |
| --------------------- | ------------------------ | ------------------------------ |
| home                  | `/`                      | HomeController@index           |
| shop                  | `/shop`                  | ShopController@index           |
| about                 | `/about`                 | AboutController@index          |
| blog                  | `/blog`                  | BlogController@index           |
| blog.show             | `/blog/{slug}`           | BlogController@show            |
| contact               | `/contact`               | ContactController@index        |
| contact.send          | `POST /contact`          | ContactController@send         |
| product               | `/product/{id}`          | ProductController@show         |
| cart                  | `/cart`                  | CartController@index           |
| cart.add              | `/cart/{product}/add`    | CartController@add             |
| cart.remove           | `/cart/{product}/remove` | CartController@remove          |
| cart.reorder          | `/cart/reorder/{order}`  | CartController@reorder         |
| checkout              | `/checkout`              | CheckoutController@index       |
| account               | `/account` (auth)        | AccountController@index        |
| order.details         | `/orders/{order}`        | OrderController@show           |
| login / login.attempt | `/login`                 | AuthController@showLogin/login |
| register.attempt      | `/register`              | AuthController@register        |
| logout                | `/logout`                | AuthController@logout          |

Controllers currently return hard-coded arrays so the UI is fully browsable
out of the box — swap those arrays for Eloquent models/queries when you wire
up a database.

## JavaScript (public/js/app.js)

Everything is data-attribute driven so Blade only needs to render the right
markup — no inline `onclick`s:

- **Nav dropdowns** — `data-dropdown` / `data-dropdown-trigger` / `data-dropdown-menu`
- **Mobile menu** — `data-mobile-menu-btn` / `data-mobile-menu-panel`
- **Carousels** (hero banner + testimonials) — `data-carousel`, `data-carousel-track`,
  `data-carousel-slide`, `data-carousel-dots`, `data-carousel-prev/next`,
  optional `data-autoplay="6000"` (ms)
- **Quantity steppers** — `data-qty` wrapping `data-qty-decrease` / `data-qty-value` /
  `data-qty-increase`; on the cart page, rows tagged `data-cart-row data-price="..."`
  automatically recalculate the summary via `data-cart-totals`
- **Product gallery** — `data-gallery-main` (big image) + `data-gallery-thumb data-full-image="..."`
- **Tabs** — `data-tabs` wrapping `data-tab-btn="key"` buttons and `data-tab-panel="key"` panels
- **Color/variant swatches** — `data-swatch-group` wrapping `data-swatch="Black"` buttons
- **Coupon form** — `data-coupon-form` + `data-coupon-banner`
- **Password show/hide** — `data-toggle-password="#input-id"`
- **Login/Register switch** — `data-auth-tab="login|register"` / `data-auth-panel="login|register"`

## Notes

- Tailwind is loaded via the CDN `<script src="https://cdn.tailwindcss.com">`
  for drop-in simplicity. For production, install Tailwind via npm and
  compile it with Vite instead of the CDN build.
- Icons are Font Awesome 6 (via cdnjs).
- Inner pages (About/Blog/Contact) share one `<x-page-banner>` component:
  tinted band, breadcrumb, uppercase eyebrow, heading, subtitle, optional CTA,
  and a photo bleeding off the right edge behind a gradient. Change the tint
  per page with `bg="bg-rose-50"` etc.
- The contact map uses a keyless OpenStreetMap embed so it renders with no
  configuration; swap `$mapEmbedUrl` in `ContactController` for a Google Maps
  embed URL if you have an API key.
- The contact form posts to `contact.send`, which validates and returns with a
  flash `status`. Wire a Mailable where the TODO comment is.
- The banner types match the original design exactly: the **hero** is a
  full-bleed section-width carousel with autoplay + dots + arrows; the two
  **promo cards** on the home page are small, side-by-side, non-carousel
  banners (green/rose tinted backgrounds, image thumbnail, single CTA).
