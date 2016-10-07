<?php

namespace Scopus\Response;

class Source
{
    /** @var array */
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }
    
    public function getTitle()
    {
        return $this->data['sourcetitle'];
    }
    
    public function getTitleAbbrev()
    {
        return $this->data['sourcetitle-abbrev'];
    }
    
    protected function getPageRange()
    {
        return $this->data['volisspag']['pagerange'];
    }
    
    public function getStartPage()
    {
        $pageRange = $this->getPageRange();
        return isset($pageRange['@first']) ? $pageRange['@first'] : null;
    }
    
    public function getEndPage()
    {
        $pageRange = $this->getPageRange();
        return isset($pageRange['@last']) ? $pageRange['@last'] : null;
    }
}