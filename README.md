# Laravel Backend - Sunday Learn

Aplikasi Laravel full-stack untuk platform pembelajaran online Sunday Learn dengan backend API internal.

## Fitur

- ✅ Authentication (Login/Register)
- ✅ Daftar Courses
- ✅ Detail Course
- ✅ Shopping Cart
- ✅ Checkout & Orders
- ✅ Enrollments & Progress Tracking
- ✅ Admin Dashboard
- ✅ Admin Course Management
- ✅ Admin Order Management

## Requirements

- PHP >= 8.2
- Composer
- Node.js & NPM
- PostgreSQL >= 12 (atau database lain yang didukung)
- PHP Extension: `pdo_pgsql` (untuk PostgreSQL)

### Install PHP Extension PostgreSQL

**Windows:**
1. Edit `php.ini`
2. Uncomment atau tambahkan: `extension=pdo_pgsql`
3. Uncomment atau tambahkan: `extension=pgsql`
4. Restart web server

**Linux (Ubuntu/Debian):**
```bash
sudo apt-get install php-pgsql
```

**macOS (Homebrew):**
```bash
brew install php@8.2
# Extension biasanya sudah termasuk
```

## Instalasi

1. Install dependencies:
```bash
composer install
npm install
```

2. Copy file environment:
```bash
cp .env.example .env
```

3. Setup PostgreSQL database:
```bash
# Buat database
createdb sunday_learn

# Atau menggunakan psql
psql -U postgres
CREATE DATABASE sunday_learn;
```

4. Konfigurasi `.env`:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=sunday_learn
DB_USERNAME=postgres
DB_PASSWORD=your_password

APP_URL=http://localhost:8000
API_URL=http://localhost:8000/api
```

5. Generate application key:
```bash
php artisan key:generate
```

6. Jalankan migrations:
```bash
php artisan migrate
```

7. Buat storage link untuk file uploads:
```bash
php artisan storage:link
```

8. Build assets:
```bash
npm run build
```

### Membuat User Admin Pertama

Setelah migration, buat user admin:
```bash
php artisan tinker
```

Kemudian jalankan:
```php
use App\Models\User;
use Illuminate\Support\Facades\Hash;

User::create([
    'name' => 'Admin',
    'email' => 'admin@example.com',
    'password' => Hash::make('password'),
    'role' => 'admin'
]);
```

## Menjalankan Aplikasi

### Development Mode
```bash
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Vite dev server (untuk hot reload)
npm run dev
```

### Production Mode
```bash
npm run build
php artisan serve
```

## Struktur Aplikasi

### Controllers
- `AuthController` - Handle authentication
- `HomeController` - Home page
- `CourseController` - Course listing & detail
- `CartController` - Shopping cart
- `CheckoutController` - Checkout process
- `OrderController` - Order history
- `EnrollmentController` - Enrollments & progress
- `Admin/AdminController` - Admin dashboard
- `Admin/AdminCourseController` - Course management
- `Admin/AdminOrderController` - Order management

### Services
- `ApiService` - Service class untuk memanggil REST API dari backend

### Middleware
- `ApiAuth` - Middleware untuk authentication
- `AdminOnly` - Middleware untuk admin-only routes

### Views
- Layout: `resources/views/layouts/app.blade.php`
- Components: `resources/views/components/`
- Pages: `resources/views/` (home, courses, cart, checkout, dll)

## Database Configuration

Aplikasi ini menggunakan PostgreSQL sebagai database default. Konfigurasi database ada di file `.env`:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=sunday_learn
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

### Alternatif Database

Aplikasi juga mendukung database lain:
- **SQLite**: Set `DB_CONNECTION=sqlite` dan `DB_DATABASE=database/database.sqlite`
- **MySQL**: Set `DB_CONNECTION=mysql` dan konfigurasi sesuai
- **PostgreSQL**: Set `DB_CONNECTION=pgsql` (default)

## API Configuration

Aplikasi menggunakan internal API Laravel. URL API dikonfigurasi di `.env`:

```env
API_URL=http://localhost:8000/api
```

Semua endpoint API tersedia di `/api/*` dan menggunakan session untuk authentication.

## Routes

### Public Routes
- `/` - Home
- `/login` - Login page
- `/register` - Register page
- `/courses` - Course listing
- `/courses/{id}` - Course detail

### Protected Routes (Require Auth)
- `/cart` - Shopping cart
- `/checkout` - Checkout
- `/orders` - Order history
- `/enrollments` - My enrollments
- `/enrollments/{id}` - Enrollment detail

### Admin Routes
- `/admin` - Admin dashboard
- `/admin/courses` - Course management
- `/admin/orders` - Order management

## Teknologi

- Laravel 12
- PostgreSQL
- Doctrine DBAL (untuk migration yang lebih kompleks)
- Tailwind CSS 4
- Vite
- Blade Templates

## Dependencies

### PHP Packages (Composer)
- `laravel/framework` ^12.0
- `doctrine/dbal` ^4.3 - Untuk dukungan database yang lebih baik
- `laravel/tinker` ^2.10.1

### Node Packages
- Lihat `package.json` untuk dependencies frontend

## Catatan

- Aplikasi ini menggunakan session untuk menyimpan token authentication
- Semua API calls dilakukan melalui `ApiService` ke internal API Laravel
- Pastikan PostgreSQL sudah terinstall dan extension PHP `pdo_pgsql` sudah aktif
- File uploads disimpan di `storage/app/public` dan di-link ke `public/storage`
