<?php

namespace Scopus\Response;

class AuthorProfile
{
    /** @var array */
    protected $data;
    
    /** @var AuthorName */
    protected $preferredName;
    
    /** @var AuthorName[] */
    protected $nameVariants;
    
    public function __construct(array $data)
    {
        $this->data = $data;
    }
    
    public function getPreferredName()
    {
        return $this->preferredName ?: $this->preferredName = new AuthorName($this->data['preferred-name']);
    }
    
    public function getNameVariants()
    {
        return $this->nameVariants ?: $this->nameVariants = array_map(function($nameVariant) {
            return new AuthorName($nameVariant);
        }, isset($this->data['name-variant']) ? 
            isset($this->data['name-variant']['indexed-name']) ? [$this->data['name-variant']] : $this->data['name-variant'] : 
            []);
    }
}