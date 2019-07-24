<?php

namespace App\Controller;

use App\Exception\CurrencyRateException;
use App\Service\CurrencyRateService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CurrencyRateController extends AbstractController
{
    /**
     * @var CurrencyRateService
     */
    private $currencyRateService;

    public function __construct(CurrencyRateService $currencyRateService)
    {
        $this->currencyRateService = $currencyRateService;
    }

    /**
    * @Route("/currency/{currencyFrom}/{currencyTo}", name="get_currency_rate", methods={"GET"})
    * @param $currencyFrom string
    * @param $currencyTo string
    * @param $request Request
    * @return number
    */
   public function cget($currencyFrom, $currencyTo, Request $request)
   {
       try {
           $number = (float)$request->get('number');

           return $this->currencyRateService->get($currencyFrom, $currencyTo, $number);
       } catch (CurrencyRateException $e) {
           throw new NotFoundHttpException($e->getMessage());
       } catch (\Exception $e) {
           throw new UnprocessableEntityHttpException('I wish I could give you more information');
       }
   }

    /**
     * @Route("/currency", name="create_currency_rate", methods={"POST"})
     * @param $request Request
     * @return array
     */
    public function create(Request $request)
    {
        try {
            $currency = (string)$request->get('currency');
            $rateToEur = (float)$request->get('rateToEur');
            $symbol = (string)$request->get('symbol');

            return $this->currencyRateService
                ->create($currency, $rateToEur, $symbol)
                ->toArray();
        } catch (CurrencyRateException $e) {
            throw new NotFoundHttpException($e->getMessage());
        } catch (\Exception $e) {
            throw new UnprocessableEntityHttpException("I can't just create things");
        }
    }

    /**
     * @Route("/currency", name="update_currency_rate", methods={"PUT"})
     * @param $request Request
     * @return array
     */
    public function update(Request $request)
    {
        try {
            $currency = (string)$request->get('currency');
            $rateToEur = (float)$request->get('rateToEur');
            $symbol = (string)$request->get('symbol');

            return $this->currencyRateService
                ->update($currency, $rateToEur, $symbol)
                ->toArray();
        } catch (CurrencyRateException $e) {
            throw new NotFoundHttpException($e->getMessage());
        } catch (\Exception $e) {
            throw new UnprocessableEntityHttpException("I can't just create things");
        }
    }

    /**
     * @Route("/currency/{currency}", name="create_currency_rate", methods={"DELETE"})
     * @param $currency string
     * @return array
     */
    public function delete($currency)
    {
        try {
           return $this->currencyRateService
                ->delete($currency)
                ->toArray();
        } catch (CurrencyRateException $e) {
            throw new NotFoundHttpException($e->getMessage());
        } catch (\Exception $e) {
            throw new UnprocessableEntityHttpException("I can't just create things");
        }
    }
}