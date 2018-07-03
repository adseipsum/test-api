Test Api Project
1) Stack
- PHP 5.6
- MySQL 5.6.16

2) Installation

- Clone project in to an empty folder
- Update dependencies `composer install` and set database parameters
- Create database `php bin/console doctrine:database:create`
- Create tables `php bin/console doctrine:schema:update --force`
- Valiate `php bin/console doctrine:schema:validate`

3) Tests
Run tests `./vendor/bin/simple-phpunit`
