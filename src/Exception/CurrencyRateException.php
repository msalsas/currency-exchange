<?php

namespace App\Exception;

class CurrencyRateException extends \Exception
{
    public function currencyDoesNotExist($currency)
    {
        $this->message = "Currency $currency does not exists";
        $this->code = 404;
    }

    public function rateToEurIsZeroOrLess($currency)
    {
        $this->message = "Rate to euro for currency $currency is zero or less";
        $this->code = 500;
    }

    public function currencyAlreadyExist($currency)
    {
        $this->message = "Currency $currency already exists";
        $this->code = 422;
    }
}