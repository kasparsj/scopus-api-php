<?php

namespace Scopus;

class SearchResults
{
    /** @var array */
    protected $data;
    
    /** @var Links */
    protected $links;
    
    /** @var Entry[] */
    protected $entries;
    
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getTotalResults()
    {
        return $this->data['opensearch:totalResults'];
    }
    
    public function getStartIndex()
    {
        return $this->data['opensearch:startIndex'];
    }
    
    public function getItemsPerPage()
    {
        return $this->data['opensearch:itemsPerPage'];
    }
    
    public function getQuery()
    {
        return $this->data['opensearch:Query'];
    }
    
    public function getLinks()
    {
        return $this->links ?: $this->links = new Links($this->data['link']);
    }
    
    public function getEntries()
    {
        return $this->entries ?: $this->entries = array_map(function($entry) {
            return new Entry($entry);
        }, $this->data['entry']);
    }
}