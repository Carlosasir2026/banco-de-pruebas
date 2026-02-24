# 🧬 BIO‑SCAN API (Laravel)

Backend **API REST** en **Laravel** para BIO‑SCAN. Sustituye al backend legacy PHP y se conecta **directamente** a la base de datos **PostgreSQL existente** (`nutricione_utf8`).

> ⚠️ Este repo **NO** debe incluir secretos. Los valores del `.env` (DB, claves, dominios, etc.) deben solicitarse a superiores (ver sección **“Datos del .env a solicitar”**).

---

## ✅ Stack

- PHP **8.3+**
- Laravel **12** (framework en `composer.json`)
- PostgreSQL (BD legacy)
- Laravel Sanctum (tokens Bearer)
- API REST

---

## 📦 Requisitos del sistema

### Dependencias
- PHP 8.3+ con extensiones:
  - `pdo_pgsql`
  - `mbstring`
  - `openssl`
  - `xml` (**importante** para `laravel/pint`)
  - `curl`
  - `zip`
- Composer 2.x
- PostgreSQL accesible desde el servidor donde corre la API

### Verificación rápida (Linux)
```bash
php -v
php -m | egrep "pdo_pgsql|mbstring|openssl|xml|curl|zip"
composer -V
```

---

## 🔐 Datos del `.env` a solicitar (a superiores)

Copia/pega este checklist y pide estos datos. **No inventar valores**.

### Aplicación
- `APP_ENV` (local / staging / production)
- `APP_URL` (URL pública del backend)
- `APP_DEBUG` (false en prod)
- `APP_KEY` (se genera en el servidor con `php artisan key:generate`)

### Base de datos (legacy)
- `DB_CONNECTION=pgsql`
- `DB_HOST`
- `DB_PORT` (normalmente 5432)
- `DB_DATABASE` (ej. `nutricione_utf8`)
- `DB_USERNAME` (usuario con permisos necesarios: postgres)
- `DB_PASSWORD`
- `DB_SSLMODE` (si aplica: `require`, `prefer`, etc.)

> Recomendación: pedir un usuario **dedicado** para la API con permisos controlados.

### Sanctum / Seguridad (si hay SPA o dominios concretos)
- `SANCTUM_STATEFUL_DOMAINS` (dominios del front si usan cookies; si solo token Bearer, puede quedar por defecto)
- `FRONTEND_URL` (si vais a usarlo para CORS/whitelist más adelante)

### (Opcional) Drivers para evitar crear tablas en la BD legacy
Si NO quieres añadir tablas extra (sessions/cache/jobs), pedir confirmación y usar:
- `SESSION_DRIVER=file`
- `CACHE_STORE=file`
- `QUEUE_CONNECTION=sync`

> Con esta configuración, **solo** necesitarás la tabla `personal_access_tokens` para Sanctum.

---

## 🧾 Plantilla `.env` recomendada (sin secretos)

Crea tu `.env` a partir de `.env.example` y ajusta:

```dotenv
APP_NAME="BIO-SCAN API"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=pgsql
DB_HOST=__PEDIR_A_SUPERIORES__
DB_PORT=5432
DB_DATABASE=__PEDIR_A_SUPERIORES__
DB_USERNAME=__PEDIR_A_SUPERIORES__
DB_PASSWORD=__PEDIR_A_SUPERIORES__
DB_SSLMODE=prefer

# Recomendado para NO tocar la BD legacy con tablas de laravel:
SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync

# Si usáis SPA con cookies (normalmente NO si todo va por Bearer Token):
# SANCTUM_STATEFUL_DOMAINS=localhost,localhost:3000,127.0.0.1,127.0.0.1:8000
```

---

## 🚀 Instalación (local)

```bash
git clone <URL_DEL_REPO>
cd bioscan-api

composer install

cp .env.example .env
# editar .env con los datos solicitados
php artisan key:generate

# limpiar cachés por si vienes de otra config
php artisan config:clear
php artisan cache:clear
```

### Levantar servidor
```bash
php artisan serve
# http://127.0.0.1:8000
```

---

## 🗄️ Base de datos: tablas “Laravel” mínimas

### Opción A (RECOMENDADA): solo Sanctum (mínimo impacto en BD legacy)
Este proyecto autentica con **Bearer Token** (Sanctum). Necesitas **solo** la tabla `personal_access_tokens`.

1) Asegúrate de NO usar drivers `database` para session/cache/queue (ver `.env` recomendado).

2) Crea la tabla `personal_access_tokens` en PostgreSQL (SQL):
```sql
CREATE TABLE IF NOT EXISTS personal_access_tokens (
  id BIGSERIAL PRIMARY KEY,
  tokenable_type VARCHAR(255) NOT NULL,
  tokenable_id BIGINT NOT NULL,
  name VARCHAR(255) NOT NULL,
  token VARCHAR(64) NOT NULL UNIQUE,
  abilities TEXT NULL,
  last_used_at TIMESTAMP NULL,
  expires_at TIMESTAMP NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL
);

CREATE INDEX IF NOT EXISTS personal_access_tokens_tokenable_type_tokenable_id_index
ON personal_access_tokens (tokenable_type, tokenable_id);
```

> Nota: En el repo hay varias migraciones duplicadas de `personal_access_tokens`.  
> Para producción limpia, se recomienda **quedarse con una sola** si se van a usar migraciones.

---

### Opción B: usar migraciones de Laravel (crea más tablas)
Si tu equipo **acepta** crear tablas auxiliares (`cache`, `jobs`, `sessions`, `users`, etc.) en la BD, entonces:
```bash
php artisan migrate
```

⚠️ En esta opción Laravel creará tablas como `users`, `sessions`, etc.  
**No recomendado** si queréis mantener la BD legacy lo más intacta posible.

---

## 🔑 Autenticación (Sanctum, Bearer Token)

### Login
**POST** `/api/v1/login`

Body:
```json
{
  "email": "usuario@email.com",
  "password": "password"
}
```

Respuesta (ejemplo):
```json
{
  "status": "success",
  "token": "1|xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
}
```

Usar en headers:
- `Authorization: Bearer TU_TOKEN`
- `Accept: application/json`

> Nota: actualmente las rutas no están protegidas por middleware `auth:sanctum`.  
> En roadmap se recomienda activarlo para endpoints sensibles.

---

## 📌 Endpoints (v1)

### Usuarios
- **POST** `/api/v1/register`
- **POST** `/api/v1/change-password`
- **GET** `/api/v1/usuarios/by-email?email=...`
- **GET** `/api/v1/usuarios/{id}`
- **PUT** `/api/v1/usuarios/{id}`

**IMPORTANTE (Front):** en BD legacy `usuarios.apellido_1` es **NOT NULL**.  
Aunque el `RegisterRequest` lo marque `nullable`, si mandas `apellido_1: null` fallará.

✅ Payload recomendado para registro:
```json
{
  "dni": "12345678X",
  "nombre": "Probador",
  "apellido_1": "Prueba",
  "apellido_2": "",
  "email": "prueba@mail.com",
  "password": "123456"
}
```

---

### Animales
- **GET** `/api/v1/animales?id_user=26`
- **GET** `/api/v1/animales/{id}`
- **POST** `/api/v1/animales`
- **PUT** `/api/v1/animales/{id}`
- **DELETE** `/api/v1/animales/{id}`

### Alimentos
- **GET** `/api/v1/alimentos?q=pollo`
- **GET** `/api/v1/alimentos/{id}`

### Dietas
- **GET** `/api/v1/dietas?id_animal=6`
- **GET** `/api/v1/dietas/{id}`
- **POST** `/api/v1/dietas`
- **PUT** `/api/v1/dietas/{id}`
- **DELETE** `/api/v1/dietas/{id}`

### Diet Items
- **GET** `/api/v1/dietas/{id_dieta}/items`
- **POST** `/api/v1/dietas/{id_dieta}/items`
- **PUT** `/api/v1/diet-items/{id}`
- **DELETE** `/api/v1/diet-items/{id}`

### Almacén
- **GET** `/api/v1/almacen/vegetal/enums`
- **GET** `/api/v1/almacen/vegetal`
- **GET** `/api/v1/almacen/vegetal/{id}`

- **GET** `/api/v1/almacen/carne/enums`
- **GET** `/api/v1/almacen/carne`
- **GET** `/api/v1/almacen/carne/{id}`

---

## 🧪 Tests rápidos

### Healthcheck
Laravel expone:
- `GET /up`

### Probar login con curl
```bash
curl -s -X POST "http://127.0.0.1:8000/api/v1/login" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{"email":"usuario@email.com","password":"password"}'
```

---

## 🧰 Buenas prácticas y notas de seguridad

- **Nunca** subir `.env` al repo.
- En **producción**:
  - `APP_DEBUG=false`
  - ejecutar:
    ```bash
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    ```
- Revisar CORS/whitelist antes de exponer públicamente.
- Idealmente, proteger endpoints con `auth:sanctum` y aplicar rate-limits.
- Evitar confiar en `id_user` enviado por cliente (en roadmap: derivar del usuario autenticado).

---

## 🏁 Despliegue (producción) – checklist

1) **Servidor** con Nginx/Apache + PHP-FPM 8.3
2) `composer install --no-dev --optimize-autoloader`
3) `.env` con valores oficiales
4) Permisos:
   - `storage/` y `bootstrap/cache/` escribibles por el usuario de PHP
5) Crear tabla `personal_access_tokens` (Opción A) o migrar (Opción B)
6) Cachear configuración:
   ```bash
   php artisan config:cache
   php artisan route:cache
   ```
7) Validar:
   - `GET /up`
   - login y un endpoint simple

---

## 🗺 Roadmap recomendado (próximos pasos)

- [ ] Proteger rutas con `auth:sanctum`
- [ ] Ajustar `RegisterRequest`: `apellido_1` como `required` para alinearlo con la BD
- [ ] Eliminar dependencias de `id_user` en requests (usar usuario autenticado)
- [ ] Tests automáticos
- [ ] Docker (API + PHP-FPM + Nginx) con variables por entorno

---

## 👨‍💻 Proyecto

BIO‑SCAN – Backend Laravel API.
