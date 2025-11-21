# Panduan Deployment ke cPanel menggunakan Git

Panduan lengkap untuk melakukan deployment aplikasi Laravel ke cPanel menggunakan Git.

## Prasyarat

1. **Akses cPanel** dengan fitur Git Version Control
2. **SSH Access** ke server cPanel (opsional, untuk script deployment)
3. **Repository Git** (GitHub, GitLab, atau Bitbucket)
4. **PHP >= 8.2** di server cPanel
5. **Composer** terinstall di server
6. **Node.js & NPM** terinstall di server (untuk build assets)

## Metode 1: Deployment menggunakan cPanel Git Version Control

### Langkah 1: Setup Repository di cPanel

1. Login ke **cPanel**
2. Buka **Git Version Control** (di bagian Software)
3. Klik **Create**
4. Isi form:
   - **Repository URL**: URL repository Git Anda (contoh: `https://github.com/username/sunday-laravel-frontend.git`)
   - **Repository Branch**: `main` atau `master`
   - **Repository Path**: `/home/username/repositories/sunday-laravel-frontend`
   - **Deployment Path**: `/home/username/public_html` (atau subdomain path jika menggunakan subdomain)
5. Klik **Create**

### Langkah 2: Konfigurasi Deployment Hook

cPanel akan membuat file `.cpanel.yml` di root repository. File ini sudah disediakan di project ini.

**Catatan**: Edit file `.cpanel.yml` dan sesuaikan:
- Ganti `username` dengan username cPanel Anda
- Sesuaikan path deployment jika menggunakan subdomain

### Langkah 3: Setup Environment File

1. Setelah repository di-clone, buat file `.env` di deployment path:
   ```bash
   cd /home/username/public_html
   cp .env.example .env
   ```

2. Edit file `.env` dengan konfigurasi production:
   ```env
   APP_NAME="Sunday Learn"
   APP_ENV=production
   APP_KEY=
   APP_DEBUG=false
   APP_URL=https://yourdomain.com

   # Database Configuration (OPSIONAL - lihat catatan di bawah)
   # Jika tidak menggunakan database, gunakan sqlite atau ubah SESSION_DRIVER dan CACHE_STORE ke 'file'
   DB_CONNECTION=sqlite
   # Atau jika menggunakan MySQL:
   # DB_CONNECTION=mysql
   # DB_HOST=localhost
   # DB_PORT=3306
   # DB_DATABASE=your_database
   # DB_USERNAME=your_username
   # DB_PASSWORD=your_password

   # Session & Cache Configuration (untuk menghindari database)
   SESSION_DRIVER=file
   CACHE_STORE=file
   QUEUE_CONNECTION=sync

   # API Configuration
   API_URL=https://your-api-domain.com/api
   ```

   **Catatan tentang Database:**
   - Aplikasi ini adalah frontend yang memanggil API eksternal, jadi **database tidak wajib** untuk data aplikasi
   - Laravel tetap membutuhkan database untuk session dan cache jika menggunakan driver `database`
   - **Rekomendasi**: Gunakan `SESSION_DRIVER=file` dan `CACHE_STORE=file` untuk menghindari setup database
   - Jika ingin menggunakan database untuk session/cache (lebih baik untuk production), setup database seperti di Langkah 4

3. Generate application key:
   ```bash
   php artisan key:generate
   ```

### Langkah 4: Setup Database (OPSIONAL)

**Database tidak wajib** jika Anda menggunakan `SESSION_DRIVER=file` dan `CACHE_STORE=file` di `.env`.

Jika Anda ingin menggunakan database untuk session dan cache (disarankan untuk production):

1. Buat database di cPanel (MySQL Databases)
2. Buat user database dan berikan akses
3. Update konfigurasi database di `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=localhost
   DB_PORT=3306
   DB_DATABASE=your_database
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   
   # Ubah session dan cache ke database
   SESSION_DRIVER=database
   CACHE_STORE=database
   ```
4. Jalankan migrations:
   ```bash
   php artisan migrate --force
   ```

**Migrations yang akan dibuat:**
- `sessions` - untuk menyimpan session data
- `cache` - untuk menyimpan cache data
- `jobs` - untuk queue jobs (jika menggunakan queue)

### Langkah 5: Set Permissions

Set permission yang benar untuk folder storage dan cache:

```bash
cd /home/username/public_html
chmod -R 755 storage bootstrap/cache
```

### Langkah 6: Deploy dari cPanel

1. Kembali ke **Git Version Control** di cPanel
2. Klik **Pull or Deploy** pada repository Anda
3. Pilih branch yang ingin di-deploy
4. Klik **Update from Remote**

cPanel akan otomatis menjalankan task di `.cpanel.yml`.


## Checklist Deployment

- [ ] Repository Git sudah setup di cPanel
- [ ] File `.env` sudah dikonfigurasi dengan benar
- [ ] SESSION_DRIVER dan CACHE_STORE sudah dikonfigurasi (file atau database)
- [ ] Database sudah dibuat dan dikonfigurasi (jika menggunakan database)
- [ ] Migrations sudah dijalankan (jika menggunakan database)
- [ ] Dependencies sudah diinstall (Composer & NPM)
- [ ] Assets sudah di-build (`npm run build`)
- [ ] Application key sudah di-generate
- [ ] Permissions folder sudah benar (storage, bootstrap/cache)
- [ ] Document root mengarah ke folder `public`
- [ ] PHP version >= 8.2
- [ ] APP_ENV=production dan APP_DEBUG=false
- [ ] Config, route, dan view sudah di-cache
- [ ] Storage link sudah dibuat
- [ ] Aplikasi sudah di-test di production

## Keamanan

1. **Jangan commit file `.env`** ke repository
2. **Set APP_DEBUG=false** di production
3. **Gunakan HTTPS** (setup SSL di cPanel)
4. **Backup database** secara berkala
5. **Update dependencies** secara berkala untuk security patches
6. **Gunakan strong password** untuk database

## Support

Jika mengalami masalah, cek:
- Error log di cPanel
- Laravel log: `storage/logs/laravel.log`
- Server error log di cPanel

