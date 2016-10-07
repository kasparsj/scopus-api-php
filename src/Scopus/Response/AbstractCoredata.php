<?php

namespace Scopus\Response;

class AbstractCoredata
{
    /** @var array */
    protected $data;
    
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getUrl()
    {
        return $this->data['prism:url'];
    }
    
    public function getIdentifier()
    {
        return $this->data['dc:identifier'];
    }

    public function getDoi()
    {
        return isset($this->data['prism:doi']) ? $this->data['prism:doi'] : null;
    }

    public function getTitle()
    {
        return isset($this->data['dc:title']) ? $this->data['dc:title'] : null;
    }

    public function getDescription()
    {
        return $this->data['dc:description'];
    }

    public function getPageRange()
    {
        return $this->data['prism:pageRange'];
    }

    public function getStartPage()
    {
        $pageRange = $this->getPageRange();
        if ($pageRange) {
            return explode('-', $pageRange)[0];
        }
    }

    public function getEndPage()
    {
        $pageRange = $this->getPageRange();
        if ($pageRange) {
            $startEndPage = explode('-', $pageRange);
            if (count($startEndPage) > 1) {
                return $startEndPage[1];
            }
        }
    }

    public function getCoverDate()
    {
        return $this->data['prism:coverDate'];
    }

    public function getPublicationName()
    {
        return isset($this->data['prism:publicationName']) ? $this->data['prism:publicationName'] : null;
    }
    
    public function getIssn()
    {
        return $this->data['prism:issn'];
    }

    public function getEIssn()
    {
        return $this->data['prism:eIssn'];
    }

    public function getVolume()
    {
        return $this->data['prism:volume'];
    }

    public function getCitedbyCount()
    {
        return $this->data['citedby-count'];
    }

    public function getAggregationType()
    {
        return $this->data['prism:aggregationType'];
    }
}