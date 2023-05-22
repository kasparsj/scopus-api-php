<?php

namespace Scopus\Response;

class EntrySerialTitle
{
    /** @var array */
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getTitle()
    {
        return isset($this->data['dc:title']) ? $this->data['dc:title'] : null;
    }

    public function getPublisher()
    {
        return isset($this->data['dc:publisher']) ? $this->data['dc:publisher'] : null;
    }

    /**
     * if several coverage periods, only shows first part
     *
     * e.g if periods like: (from 1992 to 1997, from 2008 to 2022)
     * will return
     * "coverageStartYear": "1992"
     * "coverageEndYear": "1997"
     */
    public function getCoverageStartYear()
    {
        return isset($this->data['coverageStartYear']) ? $this->data['coverageStartYear'] : null;
    }

    public function getCoverageEndYear()
    {
        return isset($this->data['coverageEndYear']) ? $this->data['coverageEndYear'] : null;
    }

    public function getAggregationType()
    {
        return isset($this->data['prism:aggregationType']) ? $this->data['prism:aggregationType'] : null;
    }

    public function getSourceId()
    {
        return isset($this->data['source-id']) ? $this->data['source-id'] : null;
    }

    public function getIssn()
    {
        return isset($this->data['prism:issn']) ? $this->data['prism:issn'] : null;
    }

    public function getEIssn()
    {
        return isset($this->data['prism:eIssn']) ? $this->data['prism:eIssn'] : null;
    }
}