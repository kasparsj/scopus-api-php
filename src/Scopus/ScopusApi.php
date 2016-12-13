<?php

namespace Scopus;

use Exception;
use GuzzleHttp\Client;
use Scopus\Exception\JsonException;
use Scopus\Exception\XmlException;
use Scopus\Response\Abstracts;
use Scopus\Response\Author;
use Scopus\Response\SearchResults;
use Scopus\Util\XmlUtil;

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
     * @param string $uri
     * @param array $options
     * @return array|Abstracts|Author|SearchResults
     * @throws Exception
     */
    public function retrieve($uri, array $options = [])
    {
        if (!isset($options['query']['apiKey']) && $this->apiKey) {
            $options['query']['apiKey'] = $this->apiKey;
        }
        
        $response = $this->client->get($uri, $options);
        
        if ($response->getStatusCode() === 200) {
            $body = $response->getBody();
            $contentType = $response->getHeader('Content-Type');
            if ($contentType && strpos(strtolower($contentType[0]), '/xml') !== false) {
                $xml = simplexml_load_string($body, "SimpleXMLElement", LIBXML_NOCDATA);
                if ($xml === false) {
                    $error = libxml_get_last_error();
                    throw new XmlException(sprintf('Xml response could not be parsed "%s" (%d) for %s', $error->message, $error->code, $uri), $error->code);
                } 
                $body = json_encode(XmlUtil::toArray($xml));
            }
            $json = json_decode($body, true);
            if (!is_array($json)) {
                $message = json_last_error_msg();
                $error = json_last_error();
                throw new JsonException(sprintf('Json response could not be decoded "%s" (%d) for "%s"', $message, $error, $uri), $error);
            }
            $type = key($json);
            switch ($type) {
                case 'search-results':
                    return new SearchResults($json['search-results']);
                case 'abstracts-retrieval-response':
                    return new Abstracts($json['abstracts-retrieval-response']);
                case 'abstracts-retrieval-multidoc-response':
                    return array_map(function($data) {
                        return new Abstracts($data);
                    }, $json['abstracts-retrieval-multidoc-response']['abstracts-retrieval-response']);
                case 'author-retrieval-response':
                    return new Author($json['author-retrieval-response'][0]);
                case 'author-retrieval-response-list':
                    return array_filter(array_map(function($data) {
                        if ($data['@status'] === 'found') {
                            return new Author($data);
                        }
                    }, $json['author-retrieval-response-list']['author-retrieval-response']));
                default:
                    throw new Exception(sprintf('Unsupported response type: "%s" for "%s"', $type, $uri));
            }
        }
    }

    /**
     * @param array $query
     * @return SearchResults
     */
    public function search(array $query)
    {
        return $this->retrieve(self::SEARCH_URI, [
            'query' => $query,
        ]);
    }

    /**
     * @param $scopusId
     * @param array $options
     * @return Abstracts|Abstracts[]
     * @throws Exception
     */
    public function retrieveAbstract($scopusId, array $options = [])
    {
        if (is_array($scopusId)) {
            $scopusId = implode(',', $scopusId);
        }
        if (count(explode(',', $scopusId)) > 25) {
            throw new Exception("The maximum number of 25 abstract id's exceeded!");
        }
        return $this->retrieve(self::ABSTRACT_URI . $scopusId, $options);
    }

    /**
     * @param $scopusIds
     * @param array $options
     * @return Abstracts[]
     */
    public function retrieveAbstracts($scopusIds, array $options = [])
    {
        $scopusIds = array_unique($scopusIds);
        if (count($scopusIds) > 1) {
            $chunks = array_chunk($scopusIds, 25);
            $abstracts = [];
            foreach ($chunks as $chunk) {
                $abstracts = array_merge($abstracts, array_combine($chunk, $this->retrieveAbstract($chunk, $options)));
            }
            return $abstracts;
        }
        else {
            try {
                return [
                    $scopusIds[0] => $this->retrieveAbstract($scopusIds[0], $options),
                ];
            }
            catch (Exception $e) {
                
            }
        }
    }

    /**
     * @param $authorId
     * @param array $options
     * @return Author|Author[]
     * @throws Exception
     */
    public function retrieveAuthor($authorId, array $options = [])
    {
        if (is_array($authorId)) {
            $authorId = implode(',', $authorId);
        }
        if (count(explode(',', $authorId)) > 25) {
            throw new Exception("The maximum number of 25 author id's exceeded!");
        }
        return $this->retrieve(self::AUTHOR_URI . $authorId, $options);
    }

    /**
     * @param $authorIds
     * @param array $options
     * @return Author[]
     */
    public function retrieveAuthors($authorIds, array $options = [])
    {
        $scopusIds = array_unique($authorIds);
        if (count($scopusIds) > 1) {
            $chunks = array_chunk($authorIds, 25);
            $authors = [];
            foreach ($chunks as $chunk) {
                $authors = array_merge($authors, array_combine($chunk, $this->retrieveAuthor($chunk, $options)));
            }
            return $authors;
        }
        else {
            try {
                return [
                    $authorIds[0] => $this->retrieveAuthor($authorIds[0], $options),
                ];
            }
            catch (Exception $e) {
                
            }
        }
    }
    
    public function retrieveAffiliation($affiliationId, array $options = [])
    {
        return $this->retrieve(self::AFFILIATION_URI . $affiliationId, $options);
    }
}