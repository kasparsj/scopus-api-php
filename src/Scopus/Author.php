<?php

namespace Scopus;

class Author
{
    /** @var array */
    protected $data;
    
    /** @var AuthorProfile */
    protected $profile;

    public function __construct(array $data)
    {
        $this->data = $data;
    }
    
    public function getProfile()
    {
        return $this->profile ?: $this->profile = new AuthorProfile($this->data['author-profile']);
    }
}