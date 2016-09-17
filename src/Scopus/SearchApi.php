<?php

namespace Scopus;

use GuzzleHttp\Client;

class SearchApi
{
    const BASE_URI = 'https://api.elsevier.com/content/search/scopus';
    const TIMEOUT = 2.0;
    
    protected $apiKey;
    
    /**
     * SearchApi constructor.
     * @param string $apiKey
     * @param string $baseUri
     * @param float $timeout
     */
    public function __construct($apiKey, $baseUri = self::BASE_URI, $timeout = self::TIMEOUT)
    {
        $this->apiKey = $apiKey;
        $this->client = new Client([
            'base_url' => $baseUri,
            'timeout' => $timeout,
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @return SearchQuery
     */
    public function query(array $query = array())
    {
        return new SearchQuery($this, $query);
    }

    /**
     * @param SearchQuery $query
     * @return SearchResults|null
     */
    public function search(SearchQuery $query)
    {
        $response = $this->client->get('', [
            'query' => $query->toArray()
        ]);
        if ($response->getStatusCode() === 200) {
            return new SearchResults(json_decode($response->getBody(), true));
        }
    }
}