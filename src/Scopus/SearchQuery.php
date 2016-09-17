<?php

namespace Scopus;

class SearchQuery
{
    const VIEW_STANDARD = 'STANDARD';
    const VIEW_COMPLETE = 'COMPLETE';
    
    protected $searchApi;
    
    protected $apiKey;

    protected $startIndex;

    protected $itemsPerPage;

    protected $query;

    protected $view;
    
    public function __construct(SearchApi $searchApi)
    {
        $this->searchApi = $searchApi;
        $this->apiKey = $searchApi->getApiKey();
        $this->startIndex = 0;
        $this->itemsPerPage = 25;
        $this->query = null;
        $this->view = self::VIEW_STANDARD;
    }
    
    public function getStartIndex()
    {
        return $this->startIndex;
    }

    public function setStartIndex($startIndex)
    {
        $this->startIndex = $startIndex;
    }

    public function getItemsPerPage()
    {
        return $this->itemsPerPage;
    }

    public function setItemsPerPage($itemsPerPage)
    {
        $this->itemsPerPage =  $itemsPerPage;
    }

    public function getQuery()
    {
        return $this->query;
    }

    public function setQuery(array $query)
    {
        $this->query = $query;
    }

    public function getView()
    {
        return $this->view;
    }

    public function setView($view)
    {
        $this->view = $view;
    }

    public function search()
    {
        return $this->searchApi->search($this);
    }
    
    public function toArray()
    {
        return [
            'query' => $this->query,
            'start' => $this->startIndex,
            'count' => $this->itemsPerPage,
            'view' => $this->view,
            'apiKey' => $this->apiKey,
        ];
    }
}