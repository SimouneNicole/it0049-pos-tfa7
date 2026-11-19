# POS Eloquent — Forms, Validation & File Uploads (Laravel)

A clean, minimalist Point of Sale (POS) application built in Laravel 13 for **IT0049 (Web System Technologies) - Technical Formative Assessment 7 (TFA7)**. Extending TFA6's **Liquid Glass** design system and Eloquent ORM models, this release adds validated create and edit workflows for customers and staff users (`old()` input retention + `$errors` feedback) and display-ready user avatar uploads processed with **Intervention Image (`intervention/image-laravel`)** on `Storage::disk('public')`.

- **Student:** Simoune Nicole
- **GitHub Repository:** [SimouneNicole/it0049-pos-tfa7](https://github.com/SimouneNicole/it0049-pos-tfa7)
- **Live Deployment:** [http://it0049-tfa7.freehosting.dev](http://it0049-tfa7.freehosting.dev)

---

## System Requirements

- **PHP**: 8.3 or higher (with `pdo_sqlite`, `pdo_mysql`, `mbstring`, `openssl`, `gd`, `zip`)
- **Composer**: 2.0 or higher
- **Web Server**: Apache / Nginx / PHP built-in server

---

## Installation & Setup

1. **Clone the repository**:
   ```bash
   git clone https://github.com/SimouneNicole/it0049-pos-tfa7.git
   cd it0049-pos-tfa7
   ```

2. **Install Composer dependencies**:
   ```bash
   composer install
   ```

3. **Configure Environment**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Prepare Database, Seeds & Public Storage Link**:
   ```bash
   touch database/database.sqlite
   php artisan migrate:fresh --seed
   php artisan storage:link
   ```

5. **Start the Development Server**:
   ```bash
   php artisan serve --port 8080
   ```
   Open [http://localhost:8080](http://localhost:8080) in your browser.

---

## Application Routes

| Method | Route | Route Name | Controller Action | Purpose |
|---|---|---|---|---|
| `GET` | `/` | `home` | `PageController@home` | Landing overview with mineral glass lens & quick links |
| `GET` | `/about` | `about` | `PageController@about` | System architecture & TFA7 capabilities overview |
| `GET` | `/customers` | `customers.index` | `CustomerController@index` | Customer accounts table with `+ New Customer` & `Edit` |
| `GET` | `/customers/create` | `customers.create` | `CustomerController@create` | Create customer form |
| `POST` | `/customers` | `customers.store` | `CustomerController@store` | Validate (`SaveCustomerRequest`) & insert customer |
| `GET` | `/customers/{customer}/edit` | `customers.edit` | `CustomerController@edit` | Edit customer form pre-filled with current data |
| `PUT` | `/customers/{customer}` | `customers.update` | `CustomerController@update` | Validate (`Rule::unique()->ignore()`) & update customer |
| `GET` | `/users` | `users.index` | `UserController@index` | Staff user accounts table displaying 40&times;40 avatars or placeholder |
| `GET` | `/users/create` | `users.create` | `UserController@create` | Create user form with optional avatar upload |
| `POST` | `/users` | `users.store` | `UserController@store` | Validate (`SaveUserRequest`), prepare avatar, & insert user |
| `GET` | `/users/{user}/edit` | `users.edit` | `UserController@edit` | Edit user form pre-filled with details & avatar preview |
| `PUT` | `/users/{user}` | `users.update` | `UserController@update` | Validate, crop/store avatar on `Storage::disk('public')`, & clean up old file |

---

## Validation & Avatar Storage Architecture

1. **Form Requests (`app/Http/Requests/`)**:
   - `SaveCustomerRequest`: trims inputs; requires `full_name` (`max:100`), requires valid unique `email` (`Rule::unique('customers', 'email')->ignore($customer?->id)`), and accepts optional `phone` (`max:20`).
   - `SaveUserRequest`: trims inputs and defaults blank roles to `'Staff Member'`; requires unique `username` (`regex:/^[a-zA-Z0-9._-]+$/`, `Rule::unique('users', 'username')->ignore($user?->id)`), requires `full_name` (`max:100`), validates `role` against `User::ROLES`, and validates `avatar` (`nullable|file|image|max:2048` — 2 MB max).
   - Validation failures automatically redirect back with `old()` input and `$errors` displayed in `#form-error-summary` (`role="alert"`) and field-level error messages.

2. **Display-Ready Avatar Storage (`app/Services/AvatarService.php` & Migration `0001_01_01_000004_add_avatar_to_users_table.php`)**:
   - Migration adds a nullable `avatar` (`varchar(255)`) column to `users`.
   - `AvatarService::prepareAndStore()` decodes uploads with `Intervention\Image\Laravel\Facades\Image`, crops/resizes to **256&times;256**, encodes cleanly (`PNG`, `WEBP`, or `JPEG`), stores under `avatars/<uuid>.<ext>` on `Storage::disk('public')`, and saves **only the relative path** in `users.avatar`.
   - Users without a custom avatar gracefully fall back to `public/images/avatar-placeholder.svg`; replaced or removed avatars are deleted after database persistence succeeds.

---

## Database Export & Automated Testing

- **Migrations & Seeders**: `database/migrations/` & `database/seeders/` (6 canonical records per table)
- **Standalone MySQL Export**: [`database/schema_export.sql`](database/schema_export.sql)
- **Automated Tests**:
  ```bash
  php artisan test
  ```
  Covers all TFA6 foundation tests (`PosFoundationsTest`) and TFA7 form, validation, `old()` retention, `256x256` Intervention Image resizing, `Storage::disk('public')` storage/cleanup, and placeholder fallback tests (`PosFormsAndUploadTest`).

---

## InfinityFree Deployment

1. Copy `env.production.example` to `.env.production` (gitignored) and set production environment/database values.
2. Create gitignored `.deploy.env` with `FTP_USER`, `FTP_PASS`, and `APP_URL`.
3. Package and upload:
   ```bash
   ./build-infinityfree-zip.sh
   ./deploy-infinityfree.sh
   ```
4. Visit `http://it0049-tfa7.freehosting.dev/extract.php` once to unpack into `/htdocs`, configure storage/database (with automatic SQLite fallback if MySQL is unavailable), and remove `extract.php`.

Live URL: **[http://it0049-tfa7.freehosting.dev](http://it0049-tfa7.freehosting.dev)**

---

## License

This project is open-source software licensed under the [MIT License](LICENSE).
