#!/bin/bash

# Script Deployment Laravel ke cPanel menggunakan Git
# Pastikan script ini memiliki permission execute: chmod +x deploy.sh

set -e

echo "🚀 Memulai deployment Laravel ke cPanel..."

# Konfigurasi (sesuaikan dengan environment Anda)
DEPLOY_PATH="/home/username/public_html"
BACKUP_PATH="/home/username/backups"
REPO_URL="https://github.com/username/repo.git"
BRANCH="main"

# Warna untuk output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Fungsi untuk menampilkan pesan
info() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

warn() {
    echo -e "${YELLOW}[WARN]${NC} $1"
}

error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Cek apakah path deployment ada
if [ ! -d "$DEPLOY_PATH" ]; then
    error "Path deployment tidak ditemukan: $DEPLOY_PATH"
    exit 1
fi

# Backup database dan file (opsional)
info "Membuat backup..."
BACKUP_DIR="$BACKUP_PATH/$(date +%Y%m%d_%H%M%S)"
mkdir -p "$BACKUP_DIR"

if [ -f "$DEPLOY_PATH/.env" ]; then
    cp "$DEPLOY_PATH/.env" "$BACKUP_DIR/.env"
    info "File .env di-backup"
fi

# Clone atau pull dari repository
info "Mengambil kode terbaru dari Git..."
if [ -d "$DEPLOY_PATH/.git" ]; then
    cd "$DEPLOY_PATH"
    git fetch origin
    git reset --hard origin/$BRANCH
    git clean -fd
else
    cd "$(dirname $DEPLOY_PATH)"
    rm -rf "$DEPLOY_PATH"/* "$DEPLOY_PATH"/.[^.]* 2>/dev/null || true
    git clone -b $BRANCH $REPO_URL "$DEPLOY_PATH"
    cd "$DEPLOY_PATH"
fi

# Install dependencies PHP
info "Menginstall dependencies PHP..."
composer install --no-dev --optimize-autoloader --no-interaction

# Install dependencies Node.js
info "Menginstall dependencies Node.js..."
npm install --production

# Build assets
info "Membangun assets production..."
npm run build

# Setup environment file jika belum ada
if [ ! -f "$DEPLOY_PATH/.env" ]; then
    warn "File .env tidak ditemukan. Membuat dari .env.example..."
    if [ -f "$DEPLOY_PATH/.env.example" ]; then
        cp "$DEPLOY_PATH/.env.example" "$DEPLOY_PATH/.env"
        warn "Silakan edit file .env dan jalankan: php artisan key:generate"
    else
        error "File .env.example tidak ditemukan!"
        exit 1
    fi
fi

# Generate application key jika belum ada
if ! grep -q "APP_KEY=base64:" "$DEPLOY_PATH/.env"; then
    info "Generate application key..."
    php artisan key:generate --force
fi

# Run migrations (hanya jika database dikonfigurasi)
if grep -q "DB_CONNECTION=mysql\|DB_CONNECTION=pgsql" "$DEPLOY_PATH/.env" 2>/dev/null; then
    info "Menjalankan migrations..."
    php artisan migrate --force
else
    warn "Database tidak dikonfigurasi. Migrations dilewati."
    warn "Jika menggunakan database, pastikan DB_CONNECTION sudah dikonfigurasi di .env"
fi

# Clear dan cache config
info "Mengoptimalkan aplikasi..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage link
info "Membuat storage link..."
php artisan storage:link || true

# Set permissions
info "Mengatur permissions..."
chmod -R 755 storage bootstrap/cache
chmod -R 755 "$DEPLOY_PATH/storage" "$DEPLOY_PATH/bootstrap/cache"

# Clear old cache
info "Membersihkan cache lama..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Re-cache untuk production
php artisan config:cache
php artisan route:cache
php artisan view:cache

info "✅ Deployment selesai!"
info "Backup tersimpan di: $BACKUP_DIR"
warn "Jangan lupa untuk:"
warn "1. Memeriksa file .env dan sesuaikan konfigurasi"
warn "2. Memastikan APP_ENV=production dan APP_DEBUG=false"
warn "3. Memeriksa permission folder storage dan bootstrap/cache"

