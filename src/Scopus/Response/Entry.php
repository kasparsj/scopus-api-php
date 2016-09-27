<?php

namespace Scopus\Response;

class Entry
{
    /** @var array */
    protected $data;
    
    /** @var EntryLinks */
    protected $links;
    
    /** @var Affiliation[] */
    protected $affiliations;
    
    /** @var EntryAuthor[] */
    protected $authors;
    
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getLinks()
    {
        return $this->links ?: $this->links = new EntryLinks($this->data['link']);
    }
    
    public function getUrl()
    {
        return $this->data['prism:url'];
    }
    
    public function getIdentifier()
    {
        return $this->data['dc:identifier'];
    }
    
    public function getTitle()
    {
        return isset($this->data['dc:title']) ? $this->data['dc:title'] : null;
    }
    
    public function getCreator()
    {
        return $this->data['dc:creator'];
    }

    /**
     * @return EntryAuthor
     */
    public function getCreatorAuthor()
    {
        return array_values(array_filter($this->getAuthors(), function(EntryAuthor $author) {
            return $author->getName() === $this->getCreator();
        }))[0];
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
    
    public function getCoverDisplayDate()
    {
        return $this->data['prism:coverDisplayDate'];
    }
    
    public function getDoi()
    {
        return isset($this->data['prism:doi']) ? $this->data['prism:doi'] : null;
    }
    
    public function getDescription()
    {
        return $this->data['dc:description'];
    }
    
    public function getCitedbyCount()
    {
        return $this->data['citedby-count'];
    }
    
    public function getAffiliations()
    {
        return $this->affiliations ?: $this->affiliations = array_map(function($affiliation) {
            return new Affiliation($affiliation);
        }, $this->data['affiliation']);
    }
    
    public function getAggregationType()
    {
        return $this->data['prism:aggregationType'];
    }
    
    public function getSubtype()
    {
        return $this->data['subtype'];
    }
    
    public function getSubtypeDescription()
    {
        return $this->data['subtypeDescription'];
    }

    /**
     * @return EntryAuthor[]|null
     */
    public function getAuthors()
    {
        if (isset($this->data['author'])) {
            return $this->authors ?: $this->authors = array_map(function($author) {
                return new EntryAuthor($author);
            }, $this->data['author']);
        }
    }
    
    public function getAuthkeywords()
    {
        return $this->data['authkeywords'];
    }
}