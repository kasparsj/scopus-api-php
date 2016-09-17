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
     * @param array $query
     * @param int $startIndex
     * @param int $itemsPerPage
     * @return SearchResults|null
     */
    public function query(array $query, $startIndex = 0, $itemsPerPage = 25)
    {
        $response = $this->client->get('', [
            'query' => [
                'query' => $query,
                'start' => $startIndex,
                'count' => $itemsPerPage,
                'apiKey' => $this->apiKey
            ]
        ]);
        if ($response->getStatusCode() === 200) {
            return new SearchResults(json_decode($response->getBody(), true));
        }
    }
}