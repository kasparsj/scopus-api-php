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

    public function __construct(array $data)
    {
        $this->data = $data;
    }
    
    public function getAuthorGroup()
    {
        return $this->authorGroup ?: $this->authorGroup = new AuthorGroup($this->data['author-group']);
    }
    
    public function getCorrespondence()
    {
        return $this->correspondence ?: $this->correspondence = new Correspondence($this->data['correspondence']);
    }
}