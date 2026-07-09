# ITSI — Sistema de Vinculación / Prácticas Preprofesionales

Aplicación monolítica **PHP 8 + CodeIgniter 4** (renderizado en servidor) con base de datos **MySQL/MariaDB**. Gestiona prácticas preprofesionales, servicio comunitario, actividades de educación continua, convenios, evaluaciones y documentos, con 4 portales por rol: `admin`, `coord` (coordinador), `docente` y `estudiante`.

Comandos estándar (fuente de verdad): `composer.json` (`composer test`), `spark` (`php spark serve`), `tests/README.md`.

## Cursor Cloud specific instructions

El script de actualización solo refresca dependencias (`composer install`). El arranque de servicios y la carga de datos NO están en él; hazlos manualmente como se indica abajo.

### Servicios

| Servicio | Requerido | Cómo correr en desarrollo |
| --- | --- | --- |
| App web (CodeIgniter 4) | Sí | `php spark serve --host 0.0.0.0 --port 8080` |
| MariaDB (base `itsi`) | Sí | Ver arranque de MariaDB abajo |
| SMTP (recuperación de contraseña) | No | Configurar `email.*` en `.env`; sin esto el resto funciona |
| OneDrive / Azure AD (backups remotos) | No | Ver `ONEDRIVE_SETUP.md` |

### Arranque de MariaDB (no hay systemd en el VM)

MariaDB no se inicia sola. Si no está corriendo, arráncala en segundo plano:

```bash
sudo mysqld_safe --datadir=/var/lib/mysql >/tmp/mariadb.log 2>&1 &
sudo mysqladmin ping   # debe responder "mysqld is alive"
```

El directorio de datos `/var/lib/mysql` persiste en el snapshot del VM (incluye la base `itsi` ya importada y los usuarios), así que normalmente basta con arrancar el servidor.

### Configuración (`.env`, git-ignorado, ya creado en el VM)

La app lee `.env` en la raíz. Config de desarrollo usada:

```
CI_ENVIRONMENT = development
app.baseURL = 'http://localhost:8080/'
database.default.hostname = 127.0.0.1
database.default.database = itsi
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
database.default.port = 3306
```

Gotcha de conexión: `root@localhost`/`root@127.0.0.1` se dejaron con contraseña vacía y `mysql_native_password` para permitir conexión TCP con contraseña vacía (el `root` por defecto de MariaDB usa auth por socket y `127.0.0.1` resuelve inversamente a `localhost`).

### Base de datos (ya importada; recrear solo si hace falta)

El esquema + datos de ejemplo viven en `bddITSI.sql` (no en migraciones de CI4; `app/Database/` está vacío). Recrear:

```bash
sudo mysql -e "DROP DATABASE IF EXISTS itsi; CREATE DATABASE itsi CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
sudo mysql --force itsi < bddITSI.sql
sudo mysql itsi < database/patch_documentos_habilitantes_institucion.sql
```

Gotcha del dump: `bddITSI.sql` tiene un `INSERT` mal formado en `TAB_PERIODOS_ACADEMICOS` (coma final en lugar de `;`, ~línea 1370) que fusiona la sentencia con `TAB_ACTIVIDADES_EDUCACION` y aborta ambas. Por eso se importa con `--force`; luego reinserta manualmente esos dos bloques (`sed -n '1365,1369p;1372,1385p'` cerrando el `INSERT` de períodos con `;`). Ambas tablas quedan vacías si se importa sin este arreglo.

### Credenciales de prueba (datos de ejemplo)

- Estudiantes `estud1`..`estud7` con contraseña en texto plano `123` (fuerzan cambio de contraseña al entrar).
- `admin`, `coord`, `docente` tienen hashes bcrypt cuyo texto plano no viene en el repo. En el VM se fijó `admin` / `Admin123!` (solo BD de dev) para poder probar el dashboard de administrador.

### Lint / Test / Build

- Lint: no hay herramienta configurada (sin PHP-CS-Fixer/PHPStan/ESLint).
- Test: `composer test` (PHPUnit). Nota: `ExampleDatabaseTest` falla de base porque no existe `app/Database/Seeds` (test de ejemplo del appstarter, no del proyecto); los demás pasan. Los tests usan SQLite en memoria, no MySQL.
- Build: no hay paso de build (PHP interpretado; assets front-end ya vendorizados en `public/sistema/assets/libs`).
