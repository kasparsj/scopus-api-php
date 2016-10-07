<?php

namespace Scopus\Response;

class BibrecordHead
{
    /** @var array */
    protected $data;
    
    /** @var AuthorGroup */
    protected $authorGroup;
    
    /** @var Correspondence */
    protected $correspondence;
    
    /** @var Source */
    protected $source;

    public function __construct(array $data)
    {
        $this->data = $data;
    }
    
    public function getAuthorGroup()
    {
        if (isset($this->data['author-group'])) {
            return $this->authorGroup ?: $this->authorGroup = new AuthorGroup($this->data['author-group']);
        }
    }
    
    public function getCorrespondence()
    {
        if (isset($this->data['correspondence'])) {
            return $this->correspondence ?: $this->correspondence = new Correspondence($this->data['correspondence']);
        }
    }
    
    public function getSource()
    {
        if (isset($this->data['source'])) {
            return $this->source ?: $this->source = new Source($this->data['source']);
        }
    }
}