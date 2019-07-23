<?php

namespace App\Repository;

use App\Entity\CurrencyRateInterface;

interface CurrencyRateRepositoryInterface
{
    /**
     * @param $currency string
     * @return CurrencyRateInterface
     */
    public function get($currency);
    public function create($currency, $rateToEur, $symbol);
    public function update($currency, $rateToEur, $symbol);
    public function delete($currency);
}