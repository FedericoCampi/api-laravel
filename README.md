# Laravel API Challenge

## Instalación

1. Clonar el repo
2. Ejecutar:
    composer install
    cp .env.example .env
    php artisan key:generate
3. Configurar base de datos  y JWT token en `.env`.

4. Ejecutar migraciones:
    php artisan migrate
5. Generar clave JWT:
    php artisan jwt:secret
6. Levantar servidor:
    php artisan serve
