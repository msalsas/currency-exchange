<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CurrencyRateRepository")
 * @ORM\Table(name="currency_rate")
 */
class CurrencyRate implements CurrencyRateInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $currency;

    /**
     * @ORM\Column(type="float", length=10)
     */
    private $rateToEur;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $symbol;

    /**
     * @return integer
     */
    public function getId(): integer
    {
        return $this->id;
    }

    /**
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency(string $currency)
    {
        $this->currency = $currency;
    }

    /**
     * @return float
     */
    public function getRateToEur(): float
    {
        return $this->rateToEur;
    }

    /**
     * @param float $rateToEur
     */
    public function setRateToEur(float $rateToEur)
    {
        $this->rateToEur = $rateToEur;
    }

    /**
     * @return string
     */
    public function getSymbol(): string
    {
        return $this->symbol;
    }

    /**
     * @param string $symbol
     */
    public function setSymbol(string $symbol)
    {
        $this->symbol = $symbol;
    }

    public function toArray()
    {
        return array(
            'currency' => $this->getCurrency(),
            'rateToEur' => $this->getRateToEur(),
            'symbol' => $this->getSymbol(),
        );
    }

}