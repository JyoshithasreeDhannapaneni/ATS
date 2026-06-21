# AGENTS.md

## Cursor Cloud specific instructions

### Overview

OpenCATS is a PHP 8.1 ATS (Applicant Tracking System) served by Apache with a MariaDB backend. There is no JS build step — frontend JS is vendored.

### Services

| Service | How to start | Port |
|---------|-------------|------|
| MariaDB | `sudo mysqld_safe &` | 3306 |
| Apache + PHP | `sudo apache2ctl start` | 80 |

### Running the application

1. Start MariaDB: `sudo mysqld_safe &` (wait ~3 s for readiness, verify with `sudo mysqladmin ping`)
2. Start Apache: `sudo apache2ctl start`
3. App is at `http://localhost/index.php`. Default login: **admin / admin**.

### Configuration

- `config.php` is gitignored. Create it from `config.php.example`. For local dev, set `DATABASE_HOST=127.0.0.1`, `DATABASE_USER=opencats`, `DATABASE_PASS=opencats`, `DATABASE_NAME=cats_dev`.
- `INSTALL_BLOCK` file must exist in the project root to bypass the install wizard.
- Writable dirs needed: `temp/`, `attachments/`, `uploads/`.

### Database setup (one-time)

After installing MariaDB, create the database and import the schema:

```
sudo mariadb -e "CREATE DATABASE IF NOT EXISTS cats_dev CHARACTER SET utf8 COLLATE utf8_unicode_ci;"
sudo mariadb -e "CREATE USER IF NOT EXISTS 'opencats'@'localhost' IDENTIFIED BY 'opencats';"
sudo mariadb -e "GRANT ALL PRIVILEGES ON cats_dev.* TO 'opencats'@'localhost'; FLUSH PRIVILEGES;"
sudo mariadb cats_dev < db/cats_schema.sql
```

The admin password in the schema is stored as plaintext `admin`. It must be set to `md5('admin')` = `21232f297a57a5a743894a0e4a801fc3`:

```
sudo mariadb cats_dev -e "UPDATE user SET password='21232f297a57a5a743894a0e4a801fc3' WHERE user_name='admin';"
```

### Testing

- **Unit tests**: `./vendor/bin/phpunit src/OpenCATS/Tests/UnitTests/` — some tests have pre-existing PHPUnit 9 compatibility issues (`setUp()` missing `: void` return type in `AddressParserTest` and `CompanyTest`). Run individual test files to avoid the fatal error.
- **Integration tests**: require a separate MariaDB instance on port 3307 (`integrationtestdb`). See `README-testing.md` for Docker-based setup.
- **Behat/BDD tests**: require Docker containers (Selenium + Nginx + MariaDB). See `README-testing.md`.

### Gotchas

- The `composer.lock` may be out of sync with `composer.json` (lockfile has PHPUnit 7.x, json requires ^9.5). Run `composer update` if `composer install` fails.
- The login system uses `md5()` password hashing. Raw passwords in the schema won't work — they must be MD5-hashed.
- Apache config must have `AllowOverride All` and `mod_rewrite` enabled for the app to work.
- Mail is disabled by default (`MAIL_MAILER=0`); set it to avoid SMTP connection errors during dev.
