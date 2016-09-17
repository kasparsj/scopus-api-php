<?php

namespace Scopus;

class Author
{
    /** @var array */
    protected $data;
    
    public function __construct(array $data)
    {
        $this->data = $data;
    }
    
    public function getId()
    {
        return $this->data['authid'];
    }
    
    public function getName()
    {
        return $this->data['authname'];
    }
    
    public function getGivenName()
    {
        return $this->data['given-name'];
    }
    
    public function getSurname()
    {
        return $this->data['surname'];
    }

    public function getInitials()
    {
        return $this->data['initials'];
    }

    public function getAffiliationId()
    {
        return $this->data['afid'][0]['$'];
    }
}