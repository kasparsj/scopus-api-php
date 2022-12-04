<?php

namespace Scopus;

use Exception;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Scopus\Exception\JsonException;
use Scopus\Exception\XmlException;
use Scopus\Response\AbstractCitations;
use Scopus\Response\Abstracts;
use Scopus\Response\Author;
use Scopus\Response\CitationCount;
use Scopus\Response\Entry;
use Scopus\Response\SearchResults;
use Scopus\Util\XmlUtil;

class ScopusApi
{
    const SEARCH_URI = 'https://api.elsevier.com/content/search/scopus';
    const ABSTRACT_URI = 'https://api.elsevier.com/content/abstract/scopus_id/';
    const AUTHOR_URI = 'https://api.elsevier.com/content/author/author_id/';
    const AFFILIATION_URI = 'https://api.elsevier.com/content/affiliation/affiliation_id/';
    const SEARCH_AUTHOR_URI = 'https://api.elsevier.com/content/search/author';
    const CITATION_OVERVIEW_URI = 'https://api.elsevier.com/content/abstract/citations';
    const CITATION_COUNT_URI = 'https://api.elsevier.com/content/abstract/citation-count';

    private $client;

    public function __construct(ClientInterface $httpClient)
    {
        $this->client = $httpClient;
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
     *
     * @return array|Abstracts|Author|SearchResults|AbstractCitations|CitationCount[]
     *
     * @throws Exception|GuzzleException
     */
    public function retrieve($uri, array $options = [])
    {
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
                    return array_map(function ($data) {
                        return new Abstracts($data);
                    }, $json['abstracts-retrieval-multidoc-response']['abstracts-retrieval-response']);
                case 'author-retrieval-response':
                    return new Author($json['author-retrieval-response'][0]);
                case 'author-retrieval-response-list':
                    return array_map(function ($data) {
                        if ($data['@status'] === 'found') {
                            return new Author($data);
                        }
                    }, $json['author-retrieval-response-list']['author-retrieval-response']);
                case 'abstract-citations-response':
                    return new AbstractCitations($json['abstract-citations-response']);
                case 'citation-count-response':
                    $document = $json['citation-count-response']['document'];

                    return array_map(function ($data) {
                        return new CitationCount($data);
                    }, isset($document['@status']) ? [$document] : $document);
                default:
                    throw new Exception(sprintf('Unsupported response type: "%s" for "%s"', $type, $uri));
            }
        }
    }

    /**
     * https://dev.elsevier.com/documentation/ScopusSearchAPI.wadl
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
     * I look for authors by name, surname or affiliation
     *  with https://dev.elsevier.com/documentation/AuthorSearchAPI.wadl
     *
     * @param string|null $lastName last name of author to look for
     * @param string|null $firstName first name of author to look for
     * @param string|null $affiliation affiliation of author to look for
     *
     * @param array $options -> https://dev.elsevier.com/tips/AuthorSearchTips.htm
     *
     * @return SearchResults use getEntries() method for get the array of author format -> https://dev.elsevier.com/guides/AuthorSearchViews.htm
     */
    public function searchAuthors(string $lastName = null, string $firstName = null, string $affiliation = null, array $options = [])
    {
        if (empty($lastName) && empty($firstName) && empty($affiliation)) return null;

        $query = (!empty($lastName)) ? 'authlast("' . $lastName . '")' : "";
        if (!empty($firstName)) {
            $query .= (empty($query)) ? "" : " and ";
            $query .= 'authfirst("' . $firstName . '")';
        }
        if (!empty($affiliation)) {
            $query .= (empty($query)) ? "" : " and ";
            $query .= 'affil("' . $affiliation . '")';
        }

        $query = array_merge($options, $this->query($query)->toArray());
        return $this->retrieve(self::SEARCH_AUTHOR_URI, [
            'query' => $query,
        ]);
    }

    /**
     * I recover the citations on a specific document
     * https://dev.elsevier.com/documentation/AbstractCitationAPI.wadl
     *
     * I can set a range of years to show: startYear - endYear
     *
     * @param array/String $documentId Scopus_id of the document or array of the document Scopus_id
     * @param string|null $startYear Start year range
     * @param string|null $endYear End of range year
     * @param array $options -> https://dev.elsevier.com/documentation/AbstractCitationAPI.wadl#simple
     *
     * @return AbstractCitations[] Return all quotes over the years grouped by 25 documents.
     *
     * Call the getCompactInfo() method, in the single instance, to retrieve the document citations in details (max 25 for instance),
     * Call the getTotalCompactInfo() method, in the single instance, to retrieve all documents citations  (max 25 for instance)
     */
    public function overviewCitation($documentId, string $startYear = null, string $endYear = null, array $options = [])
    {
        if ($startYear && $endYear) $options["date"] = $startYear . "-" . $endYear;
        if (!is_array($documentId)) $documentId = [$documentId];

        $responses = [];
        $pieces = array_chunk($documentId, 25);
        foreach ($pieces as $piece) {
            $options["scopus_id"] = "(" . implode(",", $piece) . ")";
            array_push($responses, $this->retrieve(self::CITATION_OVERVIEW_URI, [
                'query' => $options,
            ]));
        }
        return $responses;
    }

    /**
     * @param string|string[] $scopusId
     * @param array $options
     *
     * @return CitationCount[]
     *
     * @throws Exception
     */
    public function retrieveCitationCount($scopusId, array $options = [])
    {
        if (is_array($scopusId)) {
            $scopusId = implode(',', $scopusId);
        }

        if (count(explode(',', $scopusId)) > 25) {
            throw new Exception("The maximum number of 25 document id's exceeded!");
        }

        $options['scopus_id'] = $scopusId;

        return $this->retrieve(self::CITATION_COUNT_URI, [
            'query' => $options
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
        return $this->retrieve(self::ABSTRACT_URI . $scopusId, [
            'query' => $options
        ]);
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
        } else {
            try {
                return [
                    $scopusIds[0] => $this->retrieveAbstract($scopusIds[0], $options),
                ];
            } catch (Exception $e) {
                return [];
            }
        }
    }

    /**
     * Retrieve an author with
     * https://dev.elsevier.com/documentation/AuthorRetrievalAPI.wadl
     * @param $authorId author id
     * @param array $options -> ['view'=>'ENHANCED'] https://dev.elsevier.com/guides/AuthorRetrievalViews.htm
     * @return Author|Author[] an Author returns
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
        return $this->retrieve(self::AUTHOR_URI . $authorId,  [
            'query' => $options
        ]);
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
        } else {
            try {
                return [
                    $authorIds[0] => $this->retrieveAuthor($authorIds[0], $options),
                ];
            } catch (Exception $e) {
                return [];
            }
        }
    }

    public function retrieveAffiliation($affiliationId, array $options = [])
    {
        return $this->retrieve(self::AFFILIATION_URI . $affiliationId, $options);
    }

    /**
     * I recover the documents of a specific Author with
     * https://dev.elsevier.com/documentation/ScopusSearchAPI.wadl
     * cursor next = prendo i successivi 25
     *
     * I can set a search range: startYear < AnnoDocumento < endYear
     *  ! Warning: do not startYear <= Document Year <= endYear
     *
     * @param string $authorId AUTHOR_ID
     * @param string|null $startYear Start year range
     * @param string|null $endYear End of range year
     * @param bool $jrDocument If I only want items of type j or r
     *
     * @return Entry[] Return all articles by the selected author https://dev.elsevier.com/guides/ScopusSearchViews.htm
     */
    public function retrieveDocumentsAuthor(string $authorId, string $startYear = null, string $endYear = null, bool $jrDocument = false)
    {
        //Query parameters -> https://dev.elsevier.com/tips/ScopusSearchTips.htm        
        $query = "au-id(" . $authorId . ")";
        if ($startYear != null && $endYear != null) $query .= " and PUBYEAR > $startYear AND PUBYEAR < $endYear";
        if ($jrDocument)  $query .= " and SRCTYPE(r OR j)";

        $searchResults = $this->query($query)->withCursor()->viewComplete()->search();
        $documents =  $searchResults->getEntries(); //Entries[]

        $numDocument = $searchResults->getTotalResults() - $searchResults->countEntries();
        while ($numDocument > 0) { //recover all documents
            $cursor = $searchResults->getNextCursor();
            $searchResults = $this->query($query)->setCursor($cursor)->viewComplete()->search();
            $documents = array_merge($documents, $searchResults->getEntries());
            $numDocument -= $searchResults->countEntries();
        }
        return $documents;
    }
}
