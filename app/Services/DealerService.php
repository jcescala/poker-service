<?php
/**
 * Created by PhpStorm.
 * User: juane
 * Date: 10/30/17
 * Time: 11:17 PM
 */

namespace App\Services;
use GuzzleHttp;

class DealerService
{
    private $serviceBaseUrl = 'https://services.comparaonline.com/dealer';
    private $httpClient;
    private $serviceResponse;

    /**
     * DealerService constructor.
     */
    public function __construct()
    {
        $this->httpClient = new GuzzleHttp\Client();
    }

    public function shuffle()
    {
        $this->serviceResponse = $this->httpClient->request('POST', $this->serviceBaseUrl . '/deck', ['http_errors' => false]);
        if($this->serviceResponse->getStatusCode() === 200){
            return $this->serviceResponse->getBody();
        } else{
            return self::handleErrorMessage($this->serviceResponse->getStatusCode());
        }
    }

    public function dealHand($deckId, $cards = 5)
    {
        $requestQuery = sprintf('/deck/%s/deal/%d', $deckId, $cards);
        $this->serviceResponse = $this->httpClient->request('GET', $this->serviceBaseUrl . $requestQuery, ['http_errors' => false]);
        if($this->serviceResponse->getStatusCode() === 200){
            return [
                'success' => true,
                'data' => json_decode($this->serviceResponse->getBody(), true)
            ];
        }
        if($this->serviceResponse->getStatusCode() === 500){
            return $this->dealHand($deckId);
        }
        else{

            echo "Response code ". $this->serviceResponse->getStatusCode();

            return [
                'success' => false,
                'error' => self::handleErrorMessage($this->serviceResponse->getStatusCode())
            ];
        }

    }

    /**
     * @param $errorCode
     * @return string
     */
    public static function handleErrorMessage($errorCode)
    {
        return '';
    }
}