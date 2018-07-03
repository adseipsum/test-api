Test API Project
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
Run tests `./vendor/bin/simple-phpunit` on empty database

Use testing token '9F86D081884C7D659A2FEAA0C55AD015A3BF4F1B2B0B822CD15D6C15B0F00A08' in a header as 'HTTP_TOKEN' to access methods

