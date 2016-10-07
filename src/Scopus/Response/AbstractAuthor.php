<?php

namespace Scopus\Response;

class AbstractAuthor extends AuthorName
{
    /** @var array */
    protected $data;
    
    /** @var AuthorName */
    protected $preferredName;

    public function __construct(array $data)
    {
        parent::__construct($data, 'ce');
    }
    
    public function getId()
    {
        return $this->data['@auid'];
    }
    
    public function getSeq()
    {
        return $this->data['@seq'];   
    }
    
    public function getPreferredName()
    {
        return $this->preferredName ?: $this->preferredName = new AuthorName($this->data['preferred-name'], 'ce');
    }
}