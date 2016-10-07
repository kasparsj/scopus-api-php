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
        return isset($this->data['sourcetitle']) ? $this->data['sourcetitle'] : null;
    }
    
    public function getTitleAbbrev()
    {
        return isset($this->data['sourcetitle-abbrev']) ? $this->data['sourcetitle-abbrev'] : null;
    }
    
    protected function getVolisspag()
    {
        return isset($this->data['volisspag']) ? $this->data['volisspag'] : null;
    }
    
    protected function getPageRange()
    {
        $volisspag = $this->getVolisspag();
        return $volisspag && isset($volisspag['pagerange']) ? $volisspag['pagerange'] : null;
    }
    
    public function getStartPage()
    {
        $pageRange = $this->getPageRange();
        return $pageRange && isset($pageRange['@first']) ? $pageRange['@first'] : null;
    }
    
    public function getEndPage()
    {
        $pageRange = $this->getPageRange();
        return $pageRange && isset($pageRange['@last']) ? $pageRange['@last'] : null;
    }
}