<?php

namespace Scopus;

class EntryAuthor extends AuthorName
{
    public function __construct(array $data)
    {
        parent::__construct($data);
    }
    
    public function getId()
    {
        return $this->data['authid'];
    }
    
    public function getName()
    {
        return $this->data['authname'];
    }
    
    public function getAffiliationId()
    {
        return $this->data['afid'][0]['$'];
    }
}