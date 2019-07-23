<?php

namespace App\Test\Service;

use App\Entity\CurrencyRate;
use App\Repository\CurrencyRateRepositoryInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class AbstractCurrencyRateRepositoryTest extends KernelTestCase
{
    const DOLLAR_ID = 1;
    const DOLLAR_CURRENCY = 'dol';
    const DOLLAR_SYMBOL = '$';
    const DOLLAR_RATE_TO_EURO = 0.9;

    private $repositoryClassName;
    /** @var  EntityManager */
    private $entityManager;

    public function setUp()
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel
            ->getContainer()
            ->get('doctrine')
            ->getManager();

        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->dropDatabase();
        $schemaTool->createSchema($this->entityManager->getMetadataFactory()->getAllMetadata());
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null; // avoid memory leaks
    }

    protected function setRepositoryClassName($repositoryClassName)
    {
        $this->repositoryClassName = $repositoryClassName;
    }

    public function testGetShouldReturnNull()
    {
        /** @var CurrencyRateRepositoryInterface $currencyRateRepository */
        $currencyRateRepository = $this->getCurrencyRateRepository();

        $currencyRate = $currencyRateRepository->get(self::DOLLAR_CURRENCY);
        $this->assertEquals(null, $currencyRate);
    }

    public function testCreateShouldReturnNull()
    {
        /** @var CurrencyRateRepositoryInterface $currencyRateRepository */
        $currencyRateRepository = $this->getCurrencyRateRepository();

        $currencyRateRepository->create(self::DOLLAR_CURRENCY, self::DOLLAR_RATE_TO_EURO, self::DOLLAR_SYMBOL);
        $currencyRate = $currencyRateRepository->create(self::DOLLAR_CURRENCY, self::DOLLAR_RATE_TO_EURO, self::DOLLAR_SYMBOL);

        $this->assertEquals(null, $currencyRate);
    }

    public function testUpdateShouldReturnNull()
    {
        /** @var CurrencyRateRepositoryInterface $currencyRateRepository */
        $currencyRateRepository = $this->getCurrencyRateRepository();

        $currencyRate = $currencyRateRepository->update(self::DOLLAR_CURRENCY, self::DOLLAR_RATE_TO_EURO, self::DOLLAR_SYMBOL);

        $this->assertEquals(null, $currencyRate);
    }

    public function testGetShouldReturnCurrencyRate()
    {
        /** @var CurrencyRateRepositoryInterface $currencyRateRepository */
        $currencyRateRepository = $this->getCurrencyRateRepository();

        $currencyRateRepository->create(self::DOLLAR_CURRENCY, self::DOLLAR_RATE_TO_EURO, self::DOLLAR_SYMBOL);
        $currencyRate = $currencyRateRepository->get(self::DOLLAR_CURRENCY);

        $this->assertEquals($this->getDollarCurrencyRate(), $currencyRate);
    }

    public function testCreateShouldReturnCurrencyRate()
    {
        /** @var CurrencyRateRepositoryInterface $currencyRateRepository */
        $currencyRateRepository = $this->getCurrencyRateRepository();

        $currencyRate = $currencyRateRepository->create(self::DOLLAR_CURRENCY, self::DOLLAR_RATE_TO_EURO, self::DOLLAR_SYMBOL);

        $this->assertEquals($this->getDollarCurrencyRate(), $currencyRate);
    }

    public function testUpdateShouldReturnCurrencyRate()
    {
        /** @var CurrencyRateRepositoryInterface $currencyRateRepository */
        $currencyRateRepository = $this->getCurrencyRateRepository();

        $currencyRateRepository->create(self::DOLLAR_CURRENCY, self::DOLLAR_RATE_TO_EURO, self::DOLLAR_SYMBOL);
        $currencyRate = $currencyRateRepository->update(self::DOLLAR_CURRENCY, self::DOLLAR_RATE_TO_EURO, self::DOLLAR_SYMBOL);

        $this->assertEquals($this->getDollarCurrencyRate(), $currencyRate);
    }

    protected function getCurrencyRateRepository()
    {
        return $this->entityManager->getRepository($this->repositoryClassName);
    }

    protected function getDollarCurrencyRate()
    {
        $currencyRate = new CurrencyRate();
        $currencyRate->setId(self::DOLLAR_ID);
        $currencyRate->setCurrency(self::DOLLAR_CURRENCY);
        $currencyRate->setRateToEur(self::DOLLAR_RATE_TO_EURO);
        $currencyRate->setSymbol(self::DOLLAR_SYMBOL);

        return $currencyRate;
    }
}