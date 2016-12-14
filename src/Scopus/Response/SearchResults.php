<?php

namespace Scopus\Response;

class SearchResults
{
    /** @var array */
    protected $data;
    
    /** @var SearchLinks */
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
        return $this->links ?: $this->links = new SearchLinks($this->data['link']);
    }

    /**
     * @return Entry[]
     */
    public function getEntries()
    {
        if (isset($this->data['entry'])) {
            return $this->entries ?: $this->entries = array_map(function($entry) {
                return new Entry($entry);
            }, $this->data['entry']);
        }
    }
    
    public function countEntries()
    {
        return isset($this->data['entry']) ? count($this->data['entry']) : 0; 
    }
}