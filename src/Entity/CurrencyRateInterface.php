<?php

namespace App\Entity;

interface CurrencyRateInterface
{
    public function getId(): integer;
    public function getCurrency(): string;
    public function setCurrency(string $currency);
    public function getRateToEur(): float;
    public function setRateToEur(float $rateToEur);
    public function getSymbol(): string;
    public function setSymbol(string $symbol);
}