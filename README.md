# Laravel Frontend - Sunday Learn

Aplikasi frontend Laravel untuk platform pembelajaran online Sunday Learn yang memanggil REST API dari backend Express.js.

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

3. Konfigurasi `.env`:
```env
API_URL=http://localhost:5000/api
```

4. Generate application key:
```bash
php artisan key:generate
```

5. Build assets:
```bash
npm run build
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

## API Configuration

Pastikan backend API berjalan di URL yang sesuai dengan konfigurasi di `.env`:

```env
API_URL=http://localhost:5000/api
```

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
- Tailwind CSS 4
- Vite
- Blade Templates

## Catatan

- Aplikasi ini menggunakan session untuk menyimpan token authentication
- Semua API calls dilakukan melalui `ApiService`
- Pastikan backend API sudah berjalan sebelum menggunakan aplikasi ini
