Currency exchange REST API
==========================

Currency exchange is a REST API made with Symfony.

Documentation
-------------


Installation
------------

    git clone https://github.com/msalsas/currency-exchange.git

    cd currency-exchange

    composer install

    php bin/console doctrine:database:create (you will need php-sqlite3 as database is sqlite by default)

    php bin/console doctrine:migrations:migrate

    symfony server:start (you will need the [Symfony installer](https://symfony.com/download))


License
-------

This bundle is under the MIT license. See the complete license [in the bundle](LICENSE)

Reporting an issue or a feature request
---------------------------------------

Issues and feature requests are tracked in the [Github issue tracker](https://github.com/msalsas/currency-exchange/issues).
