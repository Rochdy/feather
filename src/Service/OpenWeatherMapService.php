<?php
namespace App\Service;

use App\Exception\CityNotFoundException;
use App\Exception\FreeTrialExceededException;
use App\Exception\InvalidApiKeyException;
use GuzzleHttp\Client;

class OpenWeatherMapService
{

    /** @var string */
    protected $city;
    /** @var string */
    protected $apiKey;
    /** @var string */
    protected $units = 'metric';
    /** @var Client*/
    protected $client;
    /** @var string */
    private $weatherRequestUrl = 'http://api.openweathermap.org/data/2.5/weather';
    /** @var int */
    private $tempDegree;
    /** @var string */
    private $weatherConditions;

    /**
     * OpenWeatherMapService constructor.
     * @param string $apiKey
     * @param Client $client
     */
    public function __construct(string $apiKey, Client $client)
    {
        $this->apiKey = $apiKey;
        $this->client = $client;
    }

    /**
     * @param string $city
     * @return $this
     */
    public function setCity(string $city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * Return readable/formatted condition
     * @return string
     * @throws CityNotFoundException
     * @throws FreeTrialExceededException
     * @throws InvalidApiKeyException
     */
    public function getFormattedWeatherCondition()
    {
        $this->sendWeatherRequest();
        return sprintf('In %s: %s, %2.0f degrees celsius', $this->city, $this->weatherConditions, $this->tempDegree);
    }

    /**
     * @return array
     */
    private function buildQueryParams()
    {
        return [
            'appid' => $this->apiKey,
            'q' => $this->city,
            'units' => $this->units
        ];
    }

    /**
     * @throws CityNotFoundException
     * @throws FreeTrialExceededException
     * @throws InvalidApiKeyException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function sendWeatherRequest()
    {
        $response = $this->client->request('GET', $this->weatherRequestUrl, [
            'query' => $this->buildQueryParams(),
            'http_errors' => false
        ]);

        switch ($response->getStatusCode()){
            case 404:
                throw new CityNotFoundException();
            case 429:
                throw new FreeTrialExceededException();
            case 401:
                throw new InvalidApiKeyException();
        }

        $data = json_decode($response->getBody());
        $this->tempDegree = $data->main->temp;
        $this->weatherConditions = $data->weather[0]->description;
    }
}
