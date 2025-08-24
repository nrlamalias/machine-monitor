# Machine Monitor (Laravel 12)

Daftar mesin: http://127.0.0.1:8000/machines

 Requirements
- PHP >= 8.2 (via XAMPP)
- Composer
- Laravel 12.x
- SQLite3
- Git

Instruksi penggunaan ada di dalam command `machine:monitor`. Jalankan perintah:

# Untuk jalankan migrasi db
php artisan migrate

# Untuk ajlankan simulasi data mesin
php artisan machine:monitor --setup
php artisan machine:monitor

# Jalankan server Laravel:
php artisan serve

```bash
php artisan machine:monitor --setup
php artisan machine:monitor --simulate 5
php artisan machine:monitor --status
```
