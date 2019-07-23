<?php

namespace App\Repository;

use App\Entity\CurrencyRate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CurrencyRateRepository extends ServiceEntityRepository implements CurrencyRateRepositoryInterface
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CurrencyRate::class);
    }

    public function get($currency)
    {
        return $this->findOneBy(array('currency' => $currency));
    }

    public function create($currency, $rateToEur, $symbol)
    {
        if (!$this->get($currency)) {
            $currencyRate = new CurrencyRate();
            $currencyRate->setCurrency($currency);
            $currencyRate->setRateToEur($rateToEur);
            $currencyRate->setSymbol($symbol);
            $this->getEntityManager()->persist($currencyRate);
            $this->getEntityManager()->flush();

            return $currencyRate;
        }

        return null;
    }

    public function update($currency, $rateToEur, $symbol)
    {
        $currencyRate = $this->get($currency);

        if ($currencyRate) {
            $currencyRate->setCurrency($currency);
            $currencyRate->setRateToEur($rateToEur);
            $currencyRate->setSymbol($symbol);
            $this->getEntityManager()->persist($currencyRate);
            $this->getEntityManager()->flush();
        }

        return $currencyRate;
    }

    public function delete($currency)
    {
        $currencyRate = $this->get($currency);

        if ($currencyRate) {
            $this->getEntityManager()->remove($currencyRate);
            $this->getEntityManager()->flush();
        }

        return $currencyRate;
    }
}