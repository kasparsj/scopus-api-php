<?php

namespace Scopus\Response;

class AuthorName implements IAuthorName
{
    /** @var array */
    protected $data;
    
    /** @var string */
    protected $ns;
    
    public function __construct(array $data, $ns = '')
    {
        $this->data = $data;
        $this->ns = $ns ? rtrim($ns, ':').':' : '';
    }

    public function getGivenName()
    {
        return isset($this->data[$this->ns.'given-name']) ? $this->data[$this->ns.'given-name'] : null;
    }

    public function getSurname()
    {
        return $this->data[$this->ns.'surname'];
    }

    public function getInitials()
    {
        return $this->data[$this->ns.'initials'];
    }
    
    public function getIndexedName()
    {
        return $this->data[$this->ns.'indexed-name'];
    }
}