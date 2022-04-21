<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 2000 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

# Docker com PHP 8

Caso não tenha é preciso instalar o docker e docker-compose.

- [Instalar Docker](https://docs.docker.com/install/)
- [Instalar Docker Compose](https://docs.docker.com/compose/install/)

Depois de instalar basta rodar no terminal na raiz aonde está o arquivo na pasta docker `docker-compose.yml` o seguinte comando.

```bash
docker-compose up -d
```

**-d** significa que será executado em background e seu terminal não irá ficar travado e para matar o processo basta
apertar `CTRL + C` no windows ou `Command + C` no mac.

Para instalar as dependências do laravel basta usar comando `make composer-install` na pasta docker
```bash
make composer-install
```
Para gerar key do laravel basta usar `make laravel-key` na pasta docker
```bash
make laravel-key
```
Executando migrações
Executando todas as migrações pendentes do laravel `make laravel-run-all-migrations`
```bash
make laravel-run-all-migrations
```
Executando migrações
Executando todas as migrações pendentes do laravel `make laravel-run-all-migrations`
```bash
make laravel-run-all-migrations
```
Usar o servidor de desenvolvimento embutido do PHP, você pode usar o comando artisan serve: `php artisan serve`
```bash
make laravel-artisan-serve
```
## Images pré configuradas

- [Nginx](https://www.nginx.com/)
- [Apache2](https://httpd.apache.org/)
- [MySQL](https://www.mysql.com/)
- [MariaDB](https://mariadb.com/)
- [PhpMyAdmin](https://www.phpmyadmin.net/)
- [PostgreSQL](https://www.postgresql.org/)
- [Redis](https://redis.io/)
- [PHP 8 FPM](https://php.net/)
  - [PHP Modules]
    - bcmath
    - Core
    - ctype
    - curl
    - date
    - dom
    - exif
    - fileinfo
    - filter
    - ftp
    - gd
    - hash
    - iconv
    - intl
    - json
    - libxml
    - mbstring
    - mysqli
    - mysqlnd
    - openssl
    - pcre
    - PDO
    - pdo_mysql
    - pdo_pgsql
    - pdo_sqlite
    - Phar
    - posix
    - rar
    - readline
    - redis
    - Reflection
    - session
    - SimpleXML
    - soap
    - sodium
    - SPL
    - sqlite3
    - standard
    - tokenizer
    - xdebug
    - xml
    - xmlreader
    - xmlwriter
    - xsl
    - Zend OPcache
    - zip
    - zlib

  - [Zend Modules]
    - Xdebug
    - Zend OPcache