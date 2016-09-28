<?php

namespace Scopus;

use Exception;
use GuzzleHttp\Client;
use Scopus\Response\Abstracts;
use Scopus\Response\Author;
use Scopus\Response\SearchResults;

class ScopusApi
{
    const SEARCH_URI = 'https://api.elsevier.com/content/search/scopus';
    const ABSTRACT_URI = 'https://api.elsevier.com/content/abstract/scopus_id/';
    const AUTHOR_URI = 'https://api.elsevier.com/content/author/author_id/';
    const AFFILIATION_URI = 'https://api.elsevier.com/content/affiliation/affiliation_id/';
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
    public function query($query)
    {
        return new SearchQuery($this, $query);
    }

    /**
     * @param string $link
     * @param array $options
     */
    public function retrieve($uri, array $options = [])
    {
        if (!isset($options['query']['apiKey']) && $this->apiKey) {
            $options['query']['apiKey'] = $this->apiKey;
        }
        
        $response = $this->client->get($uri, $options);
        
        if ($response->getStatusCode() === 200) {
            $json = json_decode($response->getBody(), true);
            switch (key($json)) {
                case 'search-results':
                    return new SearchResults($json['search-results']);
                case 'abstracts-retrieval-response':
                    return new Abstracts($json['abstracts-retrieval-response']);
                case 'author-retrieval-response':
                    return new Author($json['author-retrieval-response'][0]);
                case 'author-retrieval-response-list':
                    return array_map(function($data) {
                        return new Author($data);
                    }, $json['author-retrieval-response-list']['author-retrieval-response']);
            }
        }
    }
    
    public function search(array $query)
    {
        return $this->retrieve(self::SEARCH_URI, [
            'query' => $query,
        ]);
    }

    public function retrieveAbstract($scopusId)
    {
        return $this->retrieve(self::ABSTRACT_URI, $scopusId);
    }

    public function retrieveAuthor($authorId)
    {
        if (is_array($authorId)) {
            $authorId = implode(',', $authorId);
        }
        if (explode(',', $authorId) > 25) {
            throw new Exception("The maximum number of 25 author id's exceeded!");
        }
        return $this->retrieve(self::AUTHOR_URI . $authorId);
    }
    
    public function retrieveAuthors($authorIds)
    {
        return $this->retrieveAuthor($authorIds);
    }
    
    public function retrieveAffiliation($affiliationId)
    {
        return $this->retrieve(self::AFFILIATION_URI . $affiliationId);
    }
}