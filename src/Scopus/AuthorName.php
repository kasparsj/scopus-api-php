<?php

namespace Scopus;

class AuthorName
{
    /** @var array */
    protected $data;
    
    public function __construct(array $data)
    {
        $this->data = $data;
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
}