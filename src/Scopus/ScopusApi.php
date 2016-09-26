<?php

namespace Scopus;

use GuzzleHttp\Client;
use Scopus\Response\Abstracts;
use Scopus\Response\Author;
use Scopus\Response\SearchResults;

class ScopusApi
{
    const SEARCH_URI = 'https://api.elsevier.com/content/search/scopus';
    const TIMEOUT = 2.0;
    
    protected $apiKey;
    
    /**
     * SearchApi constructor.
     * @param string $apiKey
     * @param float $timeout
     */
    public function __construct($apiKey, $timeout = self::TIMEOUT)
    {
        $this->apiKey = $apiKey;
        $this->client = new Client([
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
     * @param string $link
     * @param array $options
     */
    public function retrieve($uri, $options)
    {
        $response = $this->client->get($uri, $options);
        if ($response->getStatusCode() === 200) {
            $json = json_decode($response->getBody(), true);
            switch (key($json)) {
                case 'search-results':
                    return new SearchResults($json);
                    break;
                case 'abstracts-retrieval-response':
                    return new Abstracts($json);
                    break;
                case 'author-retrieval-response':
                    return new Author($json);
                    break;
            }
        }
    }
}