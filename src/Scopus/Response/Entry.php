<?php

namespace Scopus\Response;

class Entry extends AbstractCoredata implements IAbstract
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
        parent::__construct($data);
    }

    public function getLinks()
    {
        return $this->links ?: $this->links = new EntryLinks($this->data['link']);
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
        $matches = array_filter($this->getAuthors(), function(EntryAuthor $author) {
            return $author->getName() === $this->getCreator();
        });
        if ($matches) {
            return array_values($matches)[0];
        }
    }
    
    public function getCoverDisplayDate()
    {
        return $this->data['prism:coverDisplayDate'];
    }
    
    public function getAffiliations()
    {
        return $this->affiliations ?: $this->affiliations = array_map(function($affiliation) {
            return new Affiliation($affiliation);
        }, $this->data['affiliation']);
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