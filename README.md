# Práctica CakePHP 5 + MariaDB con Docker

Despliegue multi-contenedor para el laboratorio de Tecnologías de Desarrollo:
CakePHP 5 sobre PHP 8.3 + Apache (servicio `app`) y MariaDB 11.4 (servicio `db`),
conectados por la red bridge `red-laboratorio`.

## Estructura

```
practica-cakephp/
├── Dockerfile           # PHP 8.3 + Apache (intl, pdo_mysql, mbstring, rewrite, Composer)
├── docker-compose.yml   # app + db, red, volumen persistente db_data
└── app/                 # Código de CakePHP 5 (bind mount a /var/www/html)
```

## Requisitos

- Docker Desktop (en Windows con WSL2)
- VS Code (opcional)

## Puesta en marcha

```bash
# 1. Construir e iniciar los contenedores
docker compose up -d

# 2. Instalar dependencias (app/vendor no se versiona)
docker compose exec app composer install

# 3. Crear la configuración local de la app
docker compose exec app cp config/app_local.example.php config/app_local.php
```

Edita `app/config/app_local.php` y en `Datasources.default` usa:

| Clave      | Valor           |
|------------|-----------------|
| `host`     | `db` (no `localhost`) |
| `username` | `cake_user`     |
| `password` | `cake_password` |
| `database` | `cake_db`       |

Define también un `Security.salt` propio. Abre <http://localhost:8765>: debe aparecer
"CakePHP is able to connect to the database".

En macOS, si `tmp`/`logs` no son escribibles:

```bash
docker compose exec app chown -R www-data:www-data tmp logs
docker compose exec app chmod -R 775 tmp logs
```

## Migraciones y CRUD (Bake)

```bash
docker compose exec app bin/cake migrations migrate
```

El CRUD de `students` ya está generado (`bake all students`); la lista está en
<http://localhost:8765/students>.

## Verificación de la red interna

```bash
docker network inspect practica-cakephp_red-laboratorio
docker compose exec app getent hosts db
docker compose exec db mariadb-admin ping -h db -u cake_user -pcake_password
```

> `mariadb-admin` no existe en el contenedor `app`, por eso el ping se ejecuta desde `db`.

## Notas

- El `Dockerfile` pone `AllowOverride All` en `webroot` para que el `.htaccess` de
  CakePHP funcione; sin eso las rutas como `/students` devuelven 404 de Apache.
- Los datos de MariaDB persisten en el volumen `db_data` (`docker compose down` no los borra).
- Las credenciales del `docker-compose.yml` son solo para el laboratorio.

## Comandos útiles

| Comando | Acción |
|---|---|
| `docker compose up -d` | Inicia los contenedores |
| `docker compose stop` | Detiene sin destruir |
| `docker compose down` | Elimina contenedores y red (conserva volúmenes) |
| `docker compose logs -f app` | Logs en tiempo real |
| `docker compose exec app bash` | Terminal dentro del contenedor |
