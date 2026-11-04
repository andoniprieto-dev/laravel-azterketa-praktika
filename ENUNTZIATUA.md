# Examen práctico · Kirol-zentroa (polideportivo)

**Tiempo:** 2 horas · **Nota:** 10 puntos · **Chuleta:** permitida

Tienes que hacer la API de un polideportivo en Laravel. **No hay vistas Blade ni front-end**: los controladores devuelven **JSON** y las rutas van en `routes/api.php` (el prefijo `/api` se añade solo).

Para probar usa Thunder Client, Postman o `curl`, siempre con la cabecera `Accept: application/json` (así los errores de validación llegan como JSON con código 422).

Los nombres de tablas, columnas y métodos están en euskera, como en clase.

---

## 0. Arrancar el proyecto (no puntúa, pero sin esto no hay nada)

```bash
git clone <url-del-repo>
cd laravel-azterketa-praktika
composer install
cp .env.example .env          # Windows: copy .env.example .env
php artisan key:generate
```

1. En phpMyAdmin crea la base de datos `kirol_zentroa` (utf8mb4_unicode_ci).
2. Revisa los valores `DB_*` del `.env`.
3. `php artisan migrate --seed` y `php artisan serve`.
4. Abre `http://127.0.0.1:8000/api/ping` → debe salir `{"mezua":"API martxan dago"}`.

---

## Modelo de datos

```
users 1 ──── N jarduerak        (begiralea: cada actividad la dirige un monitor)
kategoriak 1 ──── N jarduerak
users N ──── M jarduerak         (inscripciones, tabla pivote: jarduera_user)
```

| Tabla | Columnas |
|---|---|
| **users** (ya existe) | + `role` (enum: `admin`, `begiralea`, `bazkidea`; por defecto `bazkidea`) · + `telefonoa` (string, opcional) |
| **kategoriak** | `id` · `izena` (string, único) · `deskribapena` (text, opcional) · timestamps |
| **jarduerak** | `id` · `izenburua` (string) · `deskribapena` (text, opcional) · `data` (dateTime) · `edukiera` (unsignedInteger) · `prezioa` (decimal 8,2) · `kategoria_id` (FK → kategoriak) · `begiralea_id` (FK → users, opcional) · timestamps |
| **jarduera_user** | `id` · `jarduera_id` (FK) · `user_id` (FK) · `ordainduta` (boolean, por defecto `false`) · timestamps · la pareja (`jarduera_id`, `user_id`) es única |

**Reglas al borrar (onDelete):**

- Una categoría **no se puede borrar** si tiene actividades.
- Si se borra un monitor, sus actividades **no se borran**: `begiralea_id` pasa a NULL.
- Al borrar una actividad o un usuario, sus inscripciones **se borran también**.

---

## Parte 1 · Enum Role y tabla users (1,5 puntos)

1. Crea el *backed enum* `app/Enums/Role.php` (`string`) con tres valores: `Admin = 'admin'`, `Begiralea = 'begiralea'`, `Bazkidea = 'bazkidea'`.
2. Con una migración **nueva**, añade las columnas `role` y `telefonoa` a la tabla `users`. No toques la migración original. El `down()` debe quitar las columnas.
3. En el modelo `User`: los dos campos en `$fillable` y `role` con *cast* al enum.

## Parte 2 · Migraciones y modelos (2 puntos)

1. Crea las migraciones de `kategoriak`, `jarduerak` y `jarduera_user` con las columnas y reglas de borrado de arriba. **Cuidado con el orden.**
2. Crea los modelos `Kategoria` y `Jarduera`. Como las tablas están en euskera, Laravel no va a adivinar sus nombres.
3. `$fillable` correctos.

## Parte 3 · Relaciones (2 puntos)

Define estas relaciones (los nombres de los métodos son obligatorios):

| Modelo | Método | Qué devuelve |
|---|---|---|
| `Kategoria` | `jarduerak()` | las actividades de la categoría |
| `Jarduera` | `kategoria()` | la categoría de la actividad |
| `Jarduera` | `begiralea()` | el usuario que dirige la actividad (`begiralea_id`) |
| `Jarduera` | `bazkideak()` | los usuarios inscritos, con `ordainduta` y timestamps |
| `User` | `gidatutakoJarduerak()` | las actividades que dirige como monitor |
| `User` | `jarduerak()` | las actividades en las que está inscrito, con `ordainduta` |

## Parte 4 · Seeders (1 punto)

Tras `php artisan migrate:fresh --seed` tiene que haber como mínimo:

- 1 admin (`admin@kirolzentroa.eus`), 2 monitores (`begiralea`) y 5 socios (`bazkidea`).
- 3 categorías (p. ej. Igeriketa, Yoga, Spinning).
- 6 actividades, cada una con categoría y monitor. Se recomienda una `JardueraFactory`.
- Cada socio inscrito al menos en una actividad.

## Parte 5 · CRUDs (3 puntos)

Usa `Route::apiResource` y un controlador por modelo (`--api`). Todas las respuestas en JSON.

**a) `/api/users`** (1 punto)

- `index`: todos los usuarios. Poder filtrar con `?role=begiralea`.
- Validación de `store`: `name` obligatorio (máx. 255) · `email` obligatorio, formato email, único · `password` obligatorio, mínimo 8 · `role` opcional, un valor del enum · `telefonoa` opcional, máx. 20.
- `update`: igual, pero `email` único **salvo para ese mismo usuario**, y `password` opcional.
- `destroy`: devuelve 204.
- La contraseña **nunca** debe salir en la respuesta.

**b) `/api/kategoriak`** (0,75 puntos)

- `show`: la categoría **con sus actividades**.
- `destroy`: si tiene actividades, no borrar y devolver **409** con un mensaje.

**c) `/api/jarduerak`** (1,25 puntos)

- `index`: actividades con su categoría y su monitor, **sin el problema N+1**.
- Validación de `store`/`update`: `izenburua` obligatorio · `data` fecha válida · `edukiera` entero, mínimo 1 · `prezioa` numérico, mínimo 0 · `kategoria_id` obligatorio y debe existir · `begiralea_id` opcional y debe existir en `users`.
- `show`: la actividad con su categoría, su monitor y los socios inscritos.

## Parte 6 · Inscripciones (0,5 puntos)

| Verbo | Ruta | Qué hace |
|---|---|---|
| POST | `/api/jarduerak/{id}/izen-emateak` | Body: `user_id`. Inscribe al usuario (`ordainduta = false`). |
| DELETE | `/api/jarduerak/{id}/izen-emateak/{userId}` | Quita al usuario de la actividad. |
| GET | `/api/users/{id}/jarduerak` | Actividades en las que está inscrito el usuario, con `ordainduta`. |

---

## Extras (máximo +1 punto)

- **+0,5** Si la actividad está llena (nº de `bazkideak` ≥ `edukiera`) o el usuario ya está inscrito, no inscribir y devolver **422**.
- **+0,5** `begiralea_id` tiene que ser un usuario con rol `begiralea` (compruébalo en la validación o en el controlador).
- **+0,5** Imagen opcional en las actividades (columna `irudia`, `image|mimes:jpeg,png,jpg,webp|max:2048`, guardada en el disco público, `storage:link`).

---

## Qué mirará quien corrija

- `php artisan migrate:fresh --seed` funciona sin errores.
- `php artisan route:list --path=api` muestra las rutas pedidas.
- Mandar datos incorrectos devuelve **422**, no 500.
- Un ID que no existe devuelve **404** (`findOrFail`).
- Código ordenado: un controlador por modelo y nombres correctos.

> La solución está en la rama `ebazpena`. ¡Inténtalo primero tú solo!
> `git checkout ebazpena` (y después `composer install` + `php artisan migrate:fresh --seed`).
