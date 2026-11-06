# Solución · notas

Archivos de la solución:

| Parte | Archivos |
|---|---|
| 1 | `app/Enums/Role.php`, `database/migrations/*_add_role_to_users_table.php`, `app/Models/User.php` |
| 2 | `database/migrations/*_create_kategoriak_table.php`, `*_create_jarduerak_table.php`, `*_create_jarduera_user_table.php`, `app/Models/Kategoria.php`, `app/Models/Jarduera.php` |
| 3 | métodos de relación en `Kategoria`, `Jarduera`, `User` |
| 4 | `database/factories/JardueraFactory.php`, `database/seeders/DatabaseSeeder.php` |
| 5–6 | `app/Http/Controllers/*`, `routes/api.php` |

## Las trampas del enunciado

1. **Orden de migraciones**: `kategoriak` → `jarduerak` → `jarduera_user`. La fecha del nombre del archivo manda.
2. **Tablas en euskera**: `protected $table = 'kategoriak'` en el modelo y `constrained('kategoriak')` en la FK. Sin eso Laravel busca `kategorias` y `jardueras`.
3. **FK fuera de convención**: `begiralea_id` apunta a `users`, así que hay que decirlo en los dos lados:
   `constrained('users')` en la migración y `belongsTo(User::class, 'begiralea_id')` / `hasMany(Jarduera::class, 'begiralea_id')` en los modelos.
4. **nullOnDelete** exige `->nullable()` **antes** de `constrained()`.
5. **Pivote**: `jarduera_user` (singulares en orden alfabético). Con ese nombre `belongsToMany` no necesita más argumentos.
6. **Email único en update**: `Rule::unique('users')->ignore($user->id)`; si no, guardar el mismo email da 422.
7. **Password**: el cast `'hashed'` del modelo lo cifra solo; `$hidden` evita que salga en el JSON.
8. **409 en kategoriak**: comprobarlo a mano con `$kategoria->jarduerak()->exists()`. Si no, `restrictOnDelete` hace que la BD lance un error 500.
9. **N+1**: `Jarduera::with(['kategoria', 'begiralea'])->get()`.

## Probar rápido (curl)

```bash
curl -H "Accept: application/json" http://127.0.0.1:8000/api/users?role=begiralea
curl -H "Accept: application/json" -X POST http://127.0.0.1:8000/api/users \
     -d name=Ane -d email=ane@x.eus -d password=12345678 -d role=begiralea
curl -H "Accept: application/json" -X DELETE http://127.0.0.1:8000/api/kategoriak/1    # 409
curl -H "Accept: application/json" -X POST http://127.0.0.1:8000/api/jarduerak/1/izen-emateak -d user_id=4
```
