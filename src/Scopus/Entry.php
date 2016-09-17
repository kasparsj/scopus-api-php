<?php

namespace Scopus;

class Entry
{
    /** @var array */
    protected $data;
    
    /** @var Affiliation[] */
    protected $affiliations;
    
    /** @var Author[] */
    protected $authors;
    
    public function __construct(array $data)
    {
        $this->data = $data;
    }
    
    public function getTitle()
    {
        return $this->data['dc:title'];
    }
    
    public function getCreator()
    {
        return $this->data['dc:creator'];
    }
    
    public function getPublicationName()
    {
        return $this->data['prism:publicationName'];
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
        return $this->data['prism:doi'];
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
    
    public function getAuthors()
    {
        return $this->authors ?: $this->authors = array_map(function($author) {
            return new Author($author);
        }, $this->data['author']);
    }
    
    public function getAuthkeywords()
    {
        return $this->data['authkeywords'];
    }
}