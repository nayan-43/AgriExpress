# AgriExpress Admin — Laravel

A full admin panel built directly against your schema (the 23 migrations
you provided): Eloquent models with the real relationships, a controller
per resource, Supabase Storage for every image upload (with gallery
preview before submit), and Blade views built from one shared layout plus
small reusable components.

## File map

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/AuthController.php        login / logout
│   │   └── Admin/
│   │       ├── DashboardController.php    real Eloquent stats + 7-day sales chart
│   │       ├── ProductController.php      + Supabase cover image & gallery upload
│   │       ├── CategoryController.php     + image & banner upload
│   │       ├── BrandController.php        + logo upload
│   │       ├── OrderController.php        list/detail/status update
│   │       ├── CustomerController.php     list/detail/block-unblock
│   │       ├── CouponController.php       CRUD
│   │       ├── ReviewController.php       moderation queue
│   │       └── SettingsController.php     general store settings (see note below)
│   └── Requests/Admin/                    FormRequests for product/category/coupon validation
├── Models/                                 One model per table in your migrations
├── Services / Support/Supabase.php         Builds a public URL from a stored path
└── Traits/UploadsToSupabase.php            upload/delete helpers, shared by every
                                             controller that handles an image

resources/views/
├── layouts/
│   ├── admin.blade.php      Master layout: sidebar + topbar + @yield('content')
│   └── guest.blade.php      Stripped-down layout for the login page
├── partials/
│   ├── sidebar.blade.php    Nav links, built from <x-nav-link>
│   └── topbar.blade.php     Search, notifications, theme toggle, user menu
├── components/               <x-...> tags, auto-discovered by Laravel
│   ├── pill.blade.php            status badges
│   ├── avatar.blade.php          colored-initials avatar
│   ├── thumb.blade.php           image OR icon-tile fallback (:src optional)
│   ├── stat-card.blade.php       dashboard stat card
│   ├── action-buttons.blade.php  edit / delete (real DELETE form) / more
│   ├── nav-link.blade.php        sidebar link, highlights from $activeNav
│   ├── pagination-bar.blade.php  wraps a LengthAwarePaginator
│   └── empty-state.blade.php     "nothing here" table row
├── admin/
│   ├── dashboard.blade.php
│   ├── products/index.blade.php, create.blade.php   (create doubles as edit)
│   ├── categories/index.blade.php, create.blade.php
│   ├── brands/index.blade.php, create.blade.php
│   ├── orders/index.blade.php, show.blade.php
│   ├── customers/index.blade.php, show.blade.php
│   ├── coupons/index.blade.php, create.blade.php
│   ├── reviews/index.blade.php
│   └── settings/index.blade.php
└── auth/login.blade.php

public/
├── css/admin.css     Design tokens (light + dark) and shared component styles
└── js/admin.js        ONLY cross-page behavior: sidebar toggle, theme toggle
                        (dispatches a `theme-changed` event), toast, "select all"
                        checkboxes. Every other script — image previews, the
                        gallery manager, dashboard charts, description-field
                        sync — lives in that one page's own @push('scripts').

database/migrations/   Your 23 migrations, unmodified
routes/web.php         Every route, wired to the controllers above
.env.example            Supabase Storage credentials
```

## Image uploads — how they work

Every image field (`main_image` + gallery on products, `image`/`banner` on
categories, `logo` on brands) goes through the same path:

1. The Blade form previews the file **client-side** the instant it's
   chosen, using `URL.createObjectURL()` — no upload happens yet, and
   nothing is sent to the server until the whole form submits.
2. On submit, the controller uploads via `App\Traits\UploadsToSupabase`,
   which writes to the `supabase` filesystem disk (`config/filesystems.php`)
   — Supabase Storage's S3-compatible endpoint, so it's Laravel's normal
   `Storage::disk(...)->put()` under the hood.
3. Only the **returned path** (e.g. `products/3f2a...c9.jpg`) is saved on
   the model — never the file itself and never a hardcoded URL. Every
   model exposes a `*_url` accessor (`$product->main_image_url`,
   `$category->image_url`, `$brand->logo_url`, `$productImage->image_url`,
   `$orderItem->image_url`) that builds the public URL on read via
   `App\Support\Supabase::url()`, so renaming the bucket later is a
   one-line `.env` change, not a data migration.

**Product gallery specifically** (`resources/views/admin/products/create.blade.php`):

- The visible file input lets the admin pick multiple images; each pick is
  _added_ to a running list (not replaced), previewed as a thumbnail grid.
- Every thumbnail has an ✕ to drop it from the list before submit — this
  works by rebuilding a `DataTransfer` object, since a real `FileList` is
  read-only.
- In edit mode, existing gallery images (already on Supabase) are shown
  separately with a checkbox — check one to delete it (and its Supabase
  file) on save; `ProductController@update` handles both the deletions and
  any newly-added files in the same request.

All of this JS lives only in that one Blade file's `@push('scripts')`
block. Nothing about it is in `public/js/admin.js`.

## Setup

1. **Composer package** — the `supabase` disk uses Laravel's `s3` driver,
   which needs:
    ```bash
    composer require league/flysystem-aws-s3-v3 "^3.0"
    ```
2. **Env vars** — copy the block from `.env.example` into your `.env` and
   fill in your Supabase project's S3 connection details (Supabase
   dashboard → Project Settings → Storage → S3 Connection). Make the
   bucket **public** (Storage → your bucket → Settings) so the generated
   URLs are viewable without a signed request.
3. **Migrate** — `php artisan migrate` using the migrations in
   `database/migrations/` (yours, copied in unmodified).
4. **Auth** — routes assume Laravel's standard `Auth::attempt()` /
   `auth` middleware against the `users` table (already has `role` and
   `status` columns from your schema). `User::scopeCustomers()` /
   `scopeAdmins()` filter by `role`.
5. **Seed an admin** so you can log in:
    ```php
    \App\Models\User::create([
        'name' => 'Admin', 'email' => 'admin@shopease.com',
        'password' => bcrypt('password'), 'role' => 'admin', 'status' => true,
    ]);
    ```

## A gap in the schema

Your migrations don't include a `settings` table, so
`SettingsController::index()` currently reads defaults from
`config('store')` (a config file you'd add) rather than the database, and
`update()` doesn't persist anywhere yet — it just flashes a success
message. Add a `settings` table (or a single-row `store_settings` table)
and wire the controller's two methods to it when you're ready; the view
and route are already in place.

## How the layout works

Every logged-in page follows the same shape:

```blade
@extends('layouts.admin')

@section('title', 'Products')
@section('active', 'products')   {{-- highlights the matching sidebar link --}}

@section('content')
    ...page markup...
@endsection

@push('scripts')
    <script>...this page's JS only...</script>
@endpush
```

`layouts/admin.blade.php` renders the sidebar and topbar once via
`@include`, then `@yield('content')` drops in the page — nothing about the
chrome is duplicated per page. The login page uses `layouts/guest.blade.php`
instead, which skips the sidebar/topbar since there's no signed-in admin
yet.

## Tailwind

Views load Tailwind from the CDN (`cdn.tailwindcss.com`) for convenience.
For production, install Tailwind via `npm`, point it at `public/css/admin.css`
as a source file, and compile a real stylesheet instead of shipping the
CDN's runtime compiler.
