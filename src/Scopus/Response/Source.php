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
    
    protected function getVoliss()
    {
        $volisspag = $this->getVolisspag();
        return $volisspag && isset($volisspag['voliss']) ? $volisspag['voliss'] : null;
    }
    
    public function getIssue()
    {
        $volIss = $this->getVoliss();
        return $volIss && isset($volIss['@issue']) ? $volIss['@issue'] : null;
    }
    
    public function getVolume()
    {
        $volIss = $this->getVoliss();
        return $volIss && isset($volIss['@volume']) ? $volIss['@volume'] : null;
    }
}