<?php
/**
 * Created by PhpStorm.
 * User: juane
 * Date: 10/30/17
 * Time: 11:17 PM
 */

namespace App\Services;
use GuzzleHttp;
use Illuminate\Support\Facades\Cache;

class DealerService
{
    private $serviceBaseUrl = 'https://services.comparaonline.com/dealer';
    private $httpClient;
    private $serviceResponse;
    CONST errorTolerance = 4;

    /**
     * DealerService constructor.
     */
    public function __construct()
    {
        $this->httpClient = new GuzzleHttp\Client();
    }

    public function shuffle($errorCounter = 0)
    {
        $this->serviceResponse = $this->httpClient->request('POST', $this->serviceBaseUrl . '/deck', ['http_errors' => false]);
        if($this->serviceResponse->getStatusCode() === 200){
            return (String) $this->serviceResponse->getBody();
        }
        if($this->serviceResponse->getStatusCode() === 500 && $errorCounter < self::errorTolerance){
            return self::shuffle($errorCounter + 1);
        }
        else{
            return self::handleErrorMessage($this->serviceResponse->getStatusCode());
        }
    }

    public function dealHand($deckId, $cards = 5, $errorCounter = 0)
    {
        $requestQuery = sprintf('/deck/%s/deal/%d', $deckId, $cards);
        $this->serviceResponse = $this->httpClient->request('GET', $this->serviceBaseUrl . $requestQuery, ['http_errors' => false]);
        if($this->serviceResponse->getStatusCode() === 200){
            return [
                'success' => true,
                'data' => json_decode($this->serviceResponse->getBody(), true)
            ];
        }
        if(($this->serviceResponse->getStatusCode() === 500 || $this->serviceResponse->getStatusCode() === 502)
        && $errorCounter < self::errorTolerance){
            return $this->dealHand($deckId);
        }
        else{
            $this->shuffle();
            return;
        }

    }

    /**
     * @param $errorCode
     * @return string
     */
    public static function handleErrorMessage($errorCode)
    {
        return $errorCode;
    }
}