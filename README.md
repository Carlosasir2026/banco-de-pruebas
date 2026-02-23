🧬 BIO-SCAN API (Laravel)

Backend API REST desarrollado en Laravel para el sistema BIO-SCAN.

Este proyecto reemplaza el backend PHP legacy y trabaja directamente sobre la base de datos existente nutricione_utf8 en PostgreSQL.

🚀 Stack Tecnológico

PHP 8.3

Laravel 11

PostgreSQL

Laravel Sanctum (autenticación por token)

Arquitectura REST

Base de datos legacy ya existente (no gestionada por migraciones)

📂 Estructura General

El proyecto trabaja con modelos en:

App\Models\Legacy\

Conexión directa a tablas existentes como:

usuarios

animales

dietas

diet_items

alimentos

almacen_carne

almacen_vegetal

No se crean tablas nuevas salvo:

migrations

personal_access_tokens (Sanctum)

🔐 Autenticación

Se utiliza Laravel Sanctum con autenticación vía Bearer Token.

Login

POST /api/v1/login

{
  "email": "usuario@email.com",
  "password": "password"
}

Respuesta:

{
  "status": "success",
  "token": "1|xxxxxxxxxxxxxxxx"
}

Luego usar en headers:

Authorization: Bearer TU_TOKEN
Accept: application/json
📌 Endpoints principales
👤 Usuarios

POST /api/v1/usuarios

GET /api/v1/usuarios/{id}

🐾 Animales

GET /api/v1/animales?id_user=26

GET /api/v1/animales/{id}

POST /api/v1/animales

PUT /api/v1/animales/{id}

DELETE /api/v1/animales/{id}

🥩 Alimentos

GET /api/v1/alimentos?q=pollo

GET /api/v1/alimentos/{id}

🥗 Dietas

GET /api/v1/dietas?id_animal=6

GET /api/v1/dietas/{id}

POST /api/v1/dietas

PUT /api/v1/dietas/{id}

DELETE /api/v1/dietas/{id}

Diet Items

GET /api/v1/dietas/{id_dieta}/items

POST /api/v1/dietas/{id_dieta}/items

PUT /api/v1/diet-items/{id}

DELETE /api/v1/diet-items/{id}

🏬 Almacén
Almacén Carne

GET /api/v1/almacen/carne

GET /api/v1/almacen/carne/enums

POST /api/v1/almacen/carne

Almacén Vegetal

GET /api/v1/almacen/vegetal

GET /api/v1/almacen/vegetal/enums

POST /api/v1/almacen/vegetal

🛠 Instalación Local
1️⃣ Clonar proyecto
git clone https://github.com/nut-tch/bioscan-api.git
cd bioscan-api
2️⃣ Instalar dependencias
composer install
3️⃣ Configurar entorno

Copiar:

cp .env.example .env

Editar .env:

APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=pgsql
DB_HOST=192.168.1.123
DB_PORT=5432
DB_DATABASE=nutricione_utf8
DB_USERNAME=api
DB_PASSWORD=TU_PASSWORD
4️⃣ Generar clave
php artisan key:generate
5️⃣ Ejecutar servidor
php artisan serve

API disponible en:

http://127.0.0.1:8000/api/v1/
⚠️ Notas Importantes

Este proyecto trabaja sobre una base de datos existente.

No se deben ejecutar migraciones destructivas.

No subir el archivo .env al repositorio.

La tabla personal_access_tokens debe existir para Sanctum.

📌 Buenas Prácticas

Nunca confiar en id_user enviado por el cliente.

Usar autenticación por token para proteger endpoints.

Validar ENUMs provenientes de PostgreSQL.

Mantener modelos Legacy separados del dominio futuro.

🔮 Roadmap

 Proteger rutas con auth:sanctum

 Eliminar dependencia de id_user en requests

 Middleware multi-tenant por usuario autenticado

 Tests automatizados

 Dockerización

👨‍💻 Autor

Proyecto BIO-SCAN – Backend Laravel API.