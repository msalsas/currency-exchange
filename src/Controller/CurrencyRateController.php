<?php

namespace App\Controller;

use App\Exception\CurrencyRateException;
use App\Service\CurrencyRateService;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

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
    * @return Response
    */
   public function cget($currencyFrom, $currencyTo, Request $request)
   {
       try {
           $number = (float)$request->get('number');

           return new Response($this->currencyRateService->get($currencyFrom, $currencyTo, $number));
       } catch (CurrencyRateException $e) {
           throw new NotFoundHttpException($e->getMessage());
       } catch (\Exception $e) {
           throw new UnprocessableEntityHttpException('I wish I could give you more information');
       }
   }

    /**
     * @Route("/currency/{currency}", name="modify_currency_rate")
     * @param $request Request
     * @param $serializer SerializerInterface
     * @param $currency string
     * @return Response
     */
    public function modify(Request $request, SerializerInterface $serializer, $currency)
    {
        try {
            $requestData = json_decode($request->getContent());
            $rateToEur = $requestData ? (float) $requestData->rateToEur : 0;
            $symbol = $requestData ? (string) $requestData->symbol : '';

            $response = new Response();

            switch ($request->getMethod()) {
                case 'POST':
                    $serialized = $serializer->serialize($this->currencyRateService
                        ->create($currency, $rateToEur, $symbol), 'json');
                    break;
                case 'PUT':
                    $serialized = $serializer->serialize($this->currencyRateService
                        ->update($currency, $rateToEur, $symbol), 'json');
                    break;
                case 'DELETE':
                    $serialized = $serializer->serialize($this->currencyRateService
                        ->delete($currency), 'json');
                    break;
                default:
                    throw new MethodNotAllowedException("Method not allowed");
            }

            $response->setContent($serialized);

            return $response;

        } catch (CurrencyRateException $e) {
            throw new NotFoundHttpException($e->getMessage());
        } catch (\Exception $e) {
            throw new UnprocessableEntityHttpException("I can't just do things");
        }
    }
}