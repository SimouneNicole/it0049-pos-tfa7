# POS Eloquent (Laravel)

A clean, minimalist Point of Sale (POS) foundation rebuilt in Laravel for **IT0049 (Web System Technologies) - Technical Formative Assessment 6 (TFA6)**. This project upgrades the POS system from static in-memory arrays to **Laravel Eloquent ORM**, backed by relational schema migrations, model seeders, and dual-database support (SQLite / MySQL), while preserving the custom **Liquid Glass** design system.

- **Student:** Simoune Nicole
- **GitHub Repository**: [SimouneNicole/it0049-pos-tfa6](https://github.com/SimouneNicole/it0049-pos-tfa6)
- **Live Deployment**: [http://it0049-tfa6.infinityfreeapp.com/](http://it0049-tfa6.infinityfreeapp.com/)

---

## System Requirements

- **PHP**: 8.2 or higher (configured with `pdo_sqlite`, `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`)
- **Composer**: 2.0 or higher
- **Web Server**: Apache / Nginx / PHP built-in server

---

## Installation & Setup

1. **Clone the repository**:
   ```bash
   git clone https://github.com/SimouneNicole/it0049-pos-tfa6.git
   cd it0049-pos-tfa6
   ```

2. **Install Composer dependencies**:
   ```bash
   composer install
   ```

3. **Configure Environment**:
   Copy `.env.example` to `.env` and generate an application encryption key:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Prepare Database & Seeds**:
   ```bash
   touch database/database.sqlite
   php artisan migrate:fresh --seed
   ```

5. **Start the Development Server**:
   ```bash
   php artisan serve --port 8080
   ```
   Open [http://localhost:8080](http://localhost:8080) in your browser.

---

## Application Routes

| Method | Route | Controller Action | Model Query | Description |
|---|---|---|---|---|
| `GET` | `/` | `\App\Http\Controllers\PageController@home` | N/A | Overview landing page with mineral glass lens hero |
| `GET` | `/about` | `\App\Http\Controllers\PageController@about` | System Architecture | System architecture and platform description |
| `GET` | `/customers` | `\App\Http\Controllers\CustomerController@index` | `App\Models\Customer::all()` | Customer accounts listing retrieved via Eloquent (6 records) |
| `GET` | `/users` | `\App\Http\Controllers\UserController@index` | `App\Models\User::all()` | Staff user accounts directory with roles via Eloquent (6 records) |

---

## Design System

The interface uses the **Liquid Glass** aesthetic:
- **Floating Navigation Capsule**: Semi-translucent pill navigation bar (`.nav-capsule`) with backdrop blur and active-route highlighting.
- **Glass Mineral Lens**: Multi-layered circular specular highlight and ambient glow on the landing overview.
- **Translucent Data Containers**: Responsive glass card containers (`.glass-table-container`) housing tabular customer and staff directories.
- **Palette**: Mineral canvas (`#EFF4F7`), ink typography (`#17212B`), muted labels (`#5F6C78`), and subtle forest accent (`#3D7068`).

---

## Database Architecture & Eloquent Models

### 1. Migrations (`database/migrations/`)
- `customers` table (`0001_01_01_000003_create_customers_table.php`):
  - `id`: BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
  - `full_name`: VARCHAR(100) NOT NULL
  - `email`: VARCHAR(100) NOT NULL UNIQUE
  - `phone`: VARCHAR(20) DEFAULT NULL
  - `created_at`, `updated_at`: TIMESTAMPS
- `users` table (`0001_01_01_000000_create_users_table.php`):
  - `id`: BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
  - `username`: VARCHAR(50) NOT NULL UNIQUE
  - `full_name`: VARCHAR(100) NOT NULL
  - `role`: VARCHAR(50) NOT NULL DEFAULT 'Staff Member'
  - `created_at`, `updated_at`: TIMESTAMPS

### 2. Eloquent Models (`app/Models/`)
- `Customer.php`: Represents the `customers` table with `$fillable = ['full_name', 'email', 'phone']`.
- `User.php`: Represents the `users` table with `$fillable = ['username', 'full_name', 'role']`.

### 3. Seeders & SQL Export
- `CustomerSeeder.php`: Populates 6 canonical customer records (Eleanor Vance, Marcus Thorne, Sophia Sterling, Lucas Gallagher, Clara Delgado, Julian Hayes).
- `UserSeeder.php`: Populates 6 canonical staff user accounts (Clara Delgado, Marcus Thorne, Eleanor Vance, Lucas Gallagher, Sophia Sterling, Julian Hayes).
- `DatabaseSeeder.php`: Runs both seeders automatically.
- `schema_export.sql`: Standalone MySQL DDL and DML export in `database/`.

---

## Automated Testing

Run the feature test suite using PHPUnit:
```bash
php artisan test
```

Verification covers:
- Landing page HTTP 200 and visual landmark elements (`.nav-capsule`, `.glass-lens`)
- About page HTTP 200 and architectural statements
- Customer directory HTTP 200 and Eloquent records rendering
- User directory HTTP 200 and staff records rendering
- Database seeder assertions verifying $\ge 5$ records per table
- Eloquent model CRUD and Carbon datetime casting validation
- Undefined routes returning standard HTTP 404 responses

---

## InfinityFree Deployment

This project includes production packaging and automated deployment scripts:
1. `build-infinityfree-zip.sh`: Packages application code, optimized production dependencies (`composer install --no-dev`), dual-mirrored CSS assets, and pre-seeded SQLite database into `it0049-pos-tfa6-infinityfree.zip`.
2. `extract.php`: Self-extracting unpacking script that deploys the archive on InfinityFree shared hosting (`/htdocs`), provisions MySQL tables from `schema_export.sql`, sets storage permissions, and self-cleans.
3. `deploy-infinityfree.sh` / `scripts/deploy_infinityfree.py`: Automated FTP deployment script communicating with `ftpupload.net`.

Live URL: **[http://it0049-tfa6.infinityfreeapp.com/](http://it0049-tfa6.infinityfreeapp.com/)**

---

## License

This project is open-source software licensed under the [MIT License](LICENSE).
