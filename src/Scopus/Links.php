<?php

namespace Scopus;

class Links
{
    protected $links;
    
    public function __construct(array $links)
    {
        $this->links = [];
        foreach ($links as $link) {
            $this->links[$link['@ref']] = $links['@href'];
        }
    }
    
    public function getSelf()
    {
        return $this->links['self'];
    }
    
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