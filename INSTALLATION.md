# Panduan Instalasi Laravel Frontend

## Prasyarat

- PHP >= 8.2
- Composer
- Node.js & NPM
- Backend API sudah berjalan (lihat folder `backend`)

## Langkah Instalasi

### 1. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 2. Konfigurasi Environment

Copy file `.env.example` ke `.env`:

```bash
cp .env.example .env
```

Edit file `.env` dan pastikan konfigurasi berikut:

```env
APP_NAME="Sunday Learn"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

# API Configuration
API_URL=http://localhost:5000/api
```

### 3. Generate Application Key

```bash
php artisan key:generate
```

### 4. Build Assets

```bash
# Development
npm run dev

# Production
npm run build
```

### 5. Jalankan Aplikasi

```bash
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Vite dev server (untuk hot reload CSS/JS)
npm run dev
```

Aplikasi akan tersedia di `http://localhost:8000`

## Konfigurasi Backend API

Pastikan backend API sudah berjalan di `http://localhost:5000` (atau sesuai konfigurasi di `.env`).

Backend API harus memiliki endpoint berikut:
- `POST /api/auth/register` - Register
- `POST /api/auth/login` - Login
- `GET /api/auth/me` - Get current user
- `GET /api/courses` - Get all courses
- `GET /api/courses/{id}` - Get course detail
- `POST /api/cart/{courseId}` - Add to cart
- `GET /api/cart` - Get cart
- `DELETE /api/cart/{courseId}` - Remove from cart
- `POST /api/orders` - Create order
- `GET /api/orders` - Get orders
- `GET /api/enrollments` - Get enrollments
- `GET /api/enrollments/{courseId}` - Get enrollment detail
- `PATCH /api/enrollments/{courseId}/progress` - Update progress
- `GET /api/admin/orders` - Get admin orders
- `PATCH /api/admin/orders/{orderId}/confirm` - Confirm payment
- `POST /api/admin/courses` - Create course
- `PUT /api/admin/courses/{id}` - Update course
- `GET /api/admin/courses/{id}` - Get course detail (admin)
- `POST /api/admin/courses/{courseId}/videos` - Add video
- `PUT /api/admin/courses/{courseId}/videos/{videoId}` - Update video
- `DELETE /api/admin/courses/{courseId}/videos/{videoId}` - Delete video

## Troubleshooting

### Error: API connection failed
- Pastikan backend API sudah berjalan
- Cek konfigurasi `API_URL` di `.env`
- Pastikan CORS sudah dikonfigurasi di backend

### Error: Session not working
- Pastikan `APP_KEY` sudah di-generate
- Cek permission folder `storage/framework/sessions`

### Error: Assets not loading
- Jalankan `npm run build` atau `npm run dev`
- Pastikan Vite server berjalan jika menggunakan `npm run dev`

## Struktur Folder

```
laravel-frontend/
├── app/
│   ├── Http/
│   │   ├── Controllers/        # Controllers
│   │   └── Middleware/         # Middleware
│   └── Services/              # API Service
├── resources/
│   ├── views/                 # Blade templates
│   ├── css/                   # CSS files
│   └── js/                    # JavaScript files
├── routes/
│   └── web.php                # Web routes
└── config/
    └── services.php           # Service configuration
```

