<?php

namespace App\Service;

use App\Entity\CurrencyRate;
use App\Entity\CurrencyRateInterface;
use App\Exception\CurrencyRateException;
use App\Repository\CurrencyRateRepositoryInterface;
use Doctrine\Common\Persistence\ObjectManager;

class CurrencyRateService
{
    /**
     * @var ObjectManager
     */
    private $entityManager;

    public function __construct(ObjectManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function get($currencyFrom, $currencyTo, $number)
    {
        $currencyRateFrom = $this->getRepository()->get($currencyFrom);
        $currencyRateTo = $this->getRepository()->get($currencyTo);

        if (!$currencyRateFrom) {
            $this->throwCurrencyNotFoundException($currencyFrom);
        }

        if (!$currencyRateTo) {
            $this->throwCurrencyNotFoundException($currencyTo);
        }

        return $this->calculateExchange($currencyRateFrom, $currencyRateTo, $number);
    }

    public function create($currency, $rateToEur, $symbol)
    {
        if ($this->getRepository()->get($currency)) {
            $this->throwCurrencyAlreadyExistsException($currency);
        }

        return $this->getRepository()->create($currency, $rateToEur, $symbol);
    }

    public function update($currency, $rateToEur, $symbol)
    {
        if (!$this->getRepository()->get($currency)) {
            $this->throwCurrencyNotFoundException($currency);
        }

        return $this->getRepository()->update($currency, $rateToEur, $symbol);
    }

    public function delete($currency)
    {
        if (!$this->getRepository()->get($currency)) {
            $this->throwCurrencyNotFoundException($currency);
        }

        return $this->getRepository()->delete($currency);
    }

    /**
     * @return CurrencyRateRepositoryInterface
     */
    protected function getRepository()
    {
        return $this->entityManager->getRepository(CurrencyRate::class);
    }

    protected function calculateExchange(CurrencyRateInterface $currencyRateFrom, CurrencyRateInterface $currencyRateTo, $number)
    {
        $rateToEurFrom = $currencyRateFrom->getRateToEur();
        $rateToEurTo = $currencyRateTo->getRateToEur();

        if ($rateToEurFrom <= 0) {
            $this->throwRateToEurZeroOrLessException($currencyRateFrom->getCurrency());
        }
        if ($rateToEurTo <= 0) {
            $this->throwRateToEurZeroOrLessException($currencyRateTo->getCurrency());
        }

        return round($number * ($rateToEurFrom / $rateToEurTo), 2);
    }

    protected function throwCurrencyNotFoundException($currency)
    {
        $exception = new CurrencyRateException();
        $exception->currencyDoesNotExist($currency);

        throw $exception;
    }

    protected function throwRateToEurZeroOrLessException($currency)
    {
        $exception = new CurrencyRateException();
        $exception->rateToEurIsZeroOrLess($currency);

        throw $exception;
    }

    protected function throwCurrencyAlreadyExistsException($currency)
    {
        $exception = new CurrencyRateException();
        $exception->currencyAlreadyExist($currency);

        throw $exception;
    }
}