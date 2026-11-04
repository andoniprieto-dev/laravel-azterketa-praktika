# Laravel azterketa praktika · Kirol-zentroa

Proyecto de práctica para el examen de Laravel (tablas + relaciones + CRUD sobre `users`, sin Blade).

- **Enunciado:** [ENUNTZIATUA.md](ENUNTZIATUA.md)
- **Solución:** rama `ebazpena` (no la mires hasta terminar)

## Arranque rápido

```bash
composer install
cp .env.example .env
php artisan key:generate
# crea la BD kirol_zentroa en phpMyAdmin
php artisan migrate --seed
php artisan serve
```

Comprueba: `http://127.0.0.1:8000/api/ping`
