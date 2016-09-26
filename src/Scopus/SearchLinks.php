<?php

namespace Scopus;

class SearchLinks extends BaseLinks
{
    public function getFirst()
    {
        return $this->links['first'];
    }
    
    public function getPrev()
    {
        return isset($this->links['prev']) ? $this->links['prev'] : null;
    }
    
    public function getNext()
    {
        return isset($this->links['next']) ? $this->links['next'] : null;
    }
    
    public function getLast()
    {
        return $this->links['last'];
    }
}