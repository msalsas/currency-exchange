Currency exchange REST API [![Build Status](https://travis-ci.org/msalsas/currency-exchange.svg?branch=master)](https://travis-ci.org/msalsas/currency-exchange)
==========================

Currency exchange is a REST API made with Symfony.

Documentation
-------------

    GET /currency/{currencyFrom}/{currencyTo}/?number=number
    example: GET /currency/eur/dol/3.45
    
    POST /currency/{currency}
    parameters: json => {rateToEur, symbol}
    example: POST /currency/eur with {rateToEur: 1, symbol: "€"}

    PUT /currency/{currency}
    parameters: json => {rateToEur, symbol}
    example: PUT /currency/eur with {rateToEur: 1, symbol: "eur"}
    
    DELETE /currency/{currency}
    example: DELETE /currency/eur

Installation
------------

    git clone https://github.com/msalsas/currency-exchange.git

    cd currency-exchange

    composer install

    php bin/console doctrine:database:create (you will need php-sqlite3 as database is sqlite by default)

    php bin/console doctrine:database:create --env=test (testing database)
    
    php bin/console doctrine:migrations:migrate

    php bin/console doctrine:migrations:migrate --env=test (testing database)

    symfony server:start (you will need the [Symfony installer](https://symfony.com/download))


License
-------

This bundle is under the MIT license. See the complete license [in the bundle](LICENSE)

Reporting an issue or a feature request
---------------------------------------

Issues and feature requests are tracked in the [Github issue tracker](https://github.com/msalsas/currency-exchange/issues).
