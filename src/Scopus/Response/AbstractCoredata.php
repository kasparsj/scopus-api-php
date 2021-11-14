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
        return isset($this->data['dc:identifier']) ? $this->data['dc:identifier'] : null;
    }

    public function getScopusId()
    {
        $identifier = $this->getIdentifier();
        if (substr($identifier, 0, 10) === 'SCOPUS_ID:') {
            return explode(':', $identifier, 2)[1];
        }
    }

    public function getEid()
    {
        return isset($this->data['eid']) ? $this->data['eid'] : null;
    }

    public function getTitle()
    {
        return isset($this->data['dc:title']) ? $this->data['dc:title'] : null;
    }

    public function getPublicationName()
    {
        return isset($this->data['prism:publicationName']) ? $this->data['prism:publicationName'] : null;
    }

    public function getEIssn()
    {
        return $this->data['prism:eIssn'];
    }

    public function getVolume()
    {
        return $this->data['prism:volume'];
    }

    public function getPageRange()
    {
        return isset($this->data['prism:pageRange']) ? $this->data['prism:pageRange'] : null;
    }

    public function getCoverDate()
    {
        return isset($this->data['prism:coverDate']) ? $this->data['prism:coverDate'] : null;;
    }

    public function getDoi()
    {
        return isset($this->data['prism:doi']) ? $this->data['prism:doi'] : null;
    }

    public function getPubmedId()
    {
        return isset($this->data['pubmed-id']) ? $this->data['pubmed-id'] : null;
    }

    public function getArticleNumber()
    {
        return isset($this->data['article-number']) ? $this->data['article-number'] : null;
    }

    public function getDescription()
    {
        return $this->data['dc:description'];
    }

    public function getCitedbyCount()
    {
        if (!isset($this->data['citedby-count'])) return 0;
        return is_array($this->data['citedby-count']) ? $this->data['citedby-count'][1]['$'] : $this->data['citedby-count'];
    }

    public function getAggregationType()
    {
        return $this->data['prism:aggregationType'];
    }

    public function getFundSponsor()
    {
        return isset($this->data['fund-sponsor']) ? $this->data['fund-sponsor'] : null;
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

    public function getIssn()
    {
        return $this->data['prism:issn'];
    }

    public function hasError()
    {
        return isset($this->data['error']) ? $this->data['error'] : false;
    }
}
