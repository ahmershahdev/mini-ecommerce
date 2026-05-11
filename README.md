# Mini E-Commerce

A small PHP and MySQL storefront with a clean UI, search suggestions, cart, checkout, and an admin panel.

## Features

- Product browsing with clean URLs
- Search suggestions (AJAX)
- Cart and checkout flow
- Admin product management
- Responsive UI using Bootstrap

## Requirements

- PHP 8.0+
- MySQL 5.7+
- Apache (XAMPP or similar)

## Setup

1. Place the project inside your web root (for XAMPP: `htdocs`).
2. Create a MySQL database named `mini_ecommerce`.
3. Import [database/schema.sql](database/schema.sql).
4. Update database credentials in [db.php](db.php) if needed.
5. Start Apache and MySQL, then open the site in your browser.

## URLs

- Storefront: `/`
- Product details: `/product/{slug}`
- Cart: `/cart`
- Checkout: `/checkout`
- Login: `/login`
- Signup: `/signup`
- Admin login: `/admin-login`
- Admin panel: `/admin-panel`

## APIs

- Search suggestions: `GET /search_suggestions.php?q=term`
- Cart API: `POST /cart_api.php` with `product_id` and optional `qty`

## Notes

- If you host the project in a subdirectory, update `robots.txt` and `sitemap.xml` to include the correct base path.
