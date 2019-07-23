<?php

namespace App\Test\Service;

use App\Entity\CurrencyRate;
use App\Exception\CurrencyRateException;
use App\Repository\CurrencyRateRepositoryInterface;
use App\Service\CurrencyRateService;
use Doctrine\Common\Persistence\ObjectManager;
use PHPUnit\Framework\TestCase;

class CurrencyRateServiceTest extends TestCase
{
    const EURO_CURRENCY = 'eur';
    const DOLLAR_CURRENCY = 'dol';
    const POUND_CURRENCY = 'gbp';

    const EURO_SYMBOL = '€';
    const DOLLAR_SYMBOL = '$';
    const POUND_SYMBOL = '£';

    const EURO_RATE_TO_EURO = 1;
    const DOLLAR_RATE_TO_EURO = 0.9;
    const POUND_RATE_TO_EURO = 1.1;

    public function testGetShouldReturnCalculatedNumberFromEuroToDollar()
    {
        $currencyRateFrom = $this->getEuroCurrencyRate();
        $currencyRateTo = $this->getDollarCurrencyRate();

        $currencyRateRepository = $this->createMock(CurrencyRateRepositoryInterface::class);
        $currencyRateRepository->expects($this->exactly(2))
            ->method('get')
            ->willReturn($currencyRateFrom, $currencyRateTo);

        $objectManager = $this->getObjectManagerMock($currencyRateRepository);

        $currencyRateService = new CurrencyRateService($objectManager, CurrencyRate::class);
        $exchange = $currencyRateService->get($currencyRateFrom->getCurrency(), $currencyRateTo->getCurrency(), 1);
        $this->assertEquals(1.11, $exchange);
    }

    public function testGetShouldReturnCalculatedNumberFromDollarToPound()
    {
        $currencyRateFrom = $this->getDollarCurrencyRate();
        $currencyRateTo = $this->getPoundCurrencyRate();

        $currencyRateRepository = $this->createMock(CurrencyRateRepositoryInterface::class);
        $currencyRateRepository->expects($this->exactly(2))
            ->method('get')
            ->willReturn($currencyRateFrom, $currencyRateTo);

        $objectManager = $this->getObjectManagerMock($currencyRateRepository);

        $currencyRateService = new CurrencyRateService($objectManager, CurrencyRate::class);
        $exchange = $currencyRateService->get($currencyRateFrom->getCurrency(), $currencyRateTo->getCurrency(), 1);
        $this->assertEquals(0.82, $exchange);
    }

    public function testGetShouldReturnCalculatedNumberFromEuroToPound()
    {
        $currencyRateFrom = $this->getEuroCurrencyRate();
        $currencyRateTo = $this->getPoundCurrencyRate();

        $currencyRateRepository = $this->createMock(CurrencyRateRepositoryInterface::class);
        $currencyRateRepository->expects($this->exactly(2))
            ->method('get')
            ->willReturn($currencyRateFrom, $currencyRateTo);

        $objectManager = $this->getObjectManagerMock($currencyRateRepository);

        $currencyRateService = new CurrencyRateService($objectManager, CurrencyRate::class);
        $exchange = $currencyRateService->get($currencyRateFrom->getCurrency(), $currencyRateTo->getCurrency(), 1);
        $this->assertEquals(0.91, $exchange);
    }

    public function testGetShouldThrowExceptionWhenRateToEurFromIsZero()
    {
        $currencyRateFrom = $this->getZeroRateCurrencyRate();
        $currencyRateTo = $this->getPoundCurrencyRate();

        $currencyRateRepository = $this->createMock(CurrencyRateRepositoryInterface::class);
        $currencyRateRepository->expects($this->exactly(2))
            ->method('get')
            ->willReturn($currencyRateFrom, $currencyRateTo);

        $objectManager = $this->getObjectManagerMock($currencyRateRepository);

        $this->expectException(CurrencyRateException::class);

        $currencyRateService = new CurrencyRateService($objectManager, CurrencyRate::class);
        $currencyRateService->get($currencyRateFrom->getCurrency(), $currencyRateTo->getCurrency(), 1);
    }

    public function testGetShouldThrowExceptionWhenRateToEurToIsZero()
    {
        $currencyRateFrom = $this->getPoundCurrencyRate();
        $currencyRateTo = $this->getZeroRateCurrencyRate();

        $currencyRateRepository = $this->createMock(CurrencyRateRepositoryInterface::class);
        $currencyRateRepository->expects($this->exactly(2))
            ->method('get')
            ->willReturn($currencyRateFrom, $currencyRateTo);

        $objectManager = $this->getObjectManagerMock($currencyRateRepository);

        $this->expectException(CurrencyRateException::class);

        $currencyRateService = new CurrencyRateService($objectManager, CurrencyRate::class);
        $currencyRateService->get($currencyRateFrom->getCurrency(), $currencyRateTo->getCurrency(), 1);
    }

    public function testCreateShouldReturnCurrentRateObject()
    {
        $currencyRate = $this->getDollarCurrencyRate();

        $currencyRateRepository = $this->createMock(CurrencyRateRepositoryInterface::class);
        $currencyRateRepository->expects($this->once())
            ->method('get')
            ->willReturn(null);
        $currencyRateRepository->expects($this->once())
            ->method('create')
            ->willReturn($currencyRate);

        $objectManager = $this->getObjectManagerMock($currencyRateRepository);

        $currencyRateService = new CurrencyRateService($objectManager, CurrencyRate::class);
        $createdCurrencyRate = $currencyRateService->create($currencyRate->getCurrency(), $currencyRate->getRateToEur(), $currencyRate->getSymbol());

        $this->assertEquals($currencyRate, $createdCurrencyRate);
    }

    public function testCreateShouldThrowExceptionWhenAlreadyExists()
    {
        $currencyRate = $this->getPoundCurrencyRate();

        $currencyRateRepository = $this->createMock(CurrencyRateRepositoryInterface::class);
        $currencyRateRepository->expects($this->once())
            ->method('get')
            ->willReturn($currencyRate);

        $objectManager = $this->getObjectManagerMock($currencyRateRepository);

        $this->expectException(CurrencyRateException::class);

        $currencyRateService = new CurrencyRateService($objectManager, CurrencyRate::class);
        $currencyRateService->create($currencyRate->getCurrency(), $currencyRate->getRateToEur(), $currencyRate->getSymbol());
    }

    public function testUpdateShouldReturnCurrentRateObject()
    {
        $currencyRate = $this->getEuroCurrencyRate();
        $currencyRateOriginal = $this->getEuroCurrencyRate();
        $currencyRateOriginal->setRateToEur(2);

        $currencyRateRepository = $this->createMock(CurrencyRateRepositoryInterface::class);
        $currencyRateRepository->expects($this->once())
            ->method('get')
            ->willReturn($currencyRateOriginal);
        $currencyRateRepository->expects($this->once())
            ->method('update')
            ->willReturn($currencyRate);

        $objectManager = $this->getObjectManagerMock($currencyRateRepository);

        $currencyRateService = new CurrencyRateService($objectManager, CurrencyRate::class);
        $updatedCurrencyRate = $currencyRateService->update($currencyRate->getCurrency(), $currencyRate->getRateToEur(), $currencyRate->getSymbol());

        $this->assertEquals($currencyRate, $updatedCurrencyRate);
    }


    public function testUpdateShouldThrowExceptionWhenDoNotExist()
    {
        $currencyRate = $this->getPoundCurrencyRate();

        $currencyRateRepository = $this->createMock(CurrencyRateRepositoryInterface::class);
        $currencyRateRepository->expects($this->once())
            ->method('get')
            ->willReturn(null);

        $objectManager = $this->getObjectManagerMock($currencyRateRepository);

        $this->expectException(CurrencyRateException::class);

        $currencyRateService = new CurrencyRateService($objectManager, CurrencyRate::class);
        $currencyRateService->update($currencyRate->getCurrency(), $currencyRate->getRateToEur(), $currencyRate->getSymbol());
    }

    protected function getObjectManagerMock(CurrencyRateRepositoryInterface $repository)
    {
        $objectManager = $this->createMock(ObjectManager::class);
        $objectManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($repository);

        return $objectManager;
    }

    protected function getEuroCurrencyRate()
    {
        $currencyRate = new CurrencyRate();
        $currencyRate->setCurrency(self::EURO_CURRENCY);
        $currencyRate->setRateToEur(self::EURO_RATE_TO_EURO);
        $currencyRate->setSymbol(self::EURO_SYMBOL);

        return $currencyRate;
    }

    protected function getDollarCurrencyRate()
    {
        $currencyRate = new CurrencyRate();
        $currencyRate->setCurrency(self::DOLLAR_CURRENCY);
        $currencyRate->setRateToEur(self::DOLLAR_RATE_TO_EURO);
        $currencyRate->setSymbol(self::DOLLAR_SYMBOL);

        return $currencyRate;
    }

    protected function getPoundCurrencyRate()
    {
        $currencyRate = new CurrencyRate();
        $currencyRate->setCurrency(self::POUND_CURRENCY);
        $currencyRate->setRateToEur(self::POUND_RATE_TO_EURO);
        $currencyRate->setSymbol(self::POUND_SYMBOL);

        return $currencyRate;
    }

    protected function getZeroRateCurrencyRate()
    {
        $currencyRate = new CurrencyRate();
        $currencyRate->setCurrency(self::POUND_CURRENCY);
        $currencyRate->setRateToEur(0);
        $currencyRate->setSymbol(self::POUND_SYMBOL);

        return $currencyRate;
    }
}