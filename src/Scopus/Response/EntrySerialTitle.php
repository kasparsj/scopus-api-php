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

    public function getCoverageStartYear()
    {
        return isset($this->data['dc:coverageStartYear']) ? $this->data['dc:coverageStartYear'] : null;
    }

    public function getCoverageEndYear()
    {
        return isset($this->data['dc:coverageEndYear']) ? $this->data['dc:coverageEndYear'] : null;
    }
}