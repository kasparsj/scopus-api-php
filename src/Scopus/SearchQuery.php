<?php

namespace Scopus;

class SearchQuery
{
    const VIEW_STANDARD = 'STANDARD';
    const VIEW_COMPLETE = 'COMPLETE';
    
    protected $searchApi;
    
    protected $apiKey;

    protected $start;

    protected $count;

    protected $query;

    protected $view;
    
    public function __construct(ScopusApi $searchApi, $query)
    {
        $this->searchApi = $searchApi;
        $this->apiKey = $searchApi->getApiKey();
        $this->query = $query;
        $this->start = 0;
        $this->count = 25;
        $this->view = self::VIEW_STANDARD;
    }
    
    public function getStart()
    {
        return $this->start;
    }

    public function start($startIndex)
    {
        $this->start = $startIndex;
        return $this;
    }

    public function getCount()
    {
        return $this->count;
    }

    public function count($count)
    {
        $this->count = $count;
        return $this;
    }

    public function getQuery()
    {
        return $this->query;
    }

    public function query($query)
    {
        $this->query = $query;
        return $this;
    }

    public function getView()
    {
        return $this->view;
    }

    public function viewStandard()
    {
        $this->view = self::VIEW_STANDARD;
        return $this;
    }
    
    public function viewComplete()
    {
        $this->view = self::VIEW_COMPLETE;
        return $this;
    }

    public function search()
    {
        return $this->searchApi->retrieve(ScopusApi::SEARCH_URI, [
            'query' => $this->toArray()
        ]);
    }
    
    public function toArray()
    {
        return [
            'query' => $this->query,
            'start' => $this->start,
            'count' => $this->count,
            'view' => $this->view,
            'apiKey' => $this->apiKey,
        ];
    }
}