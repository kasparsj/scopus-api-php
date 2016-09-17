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
        return $this->links['prev'];
    }
    
    public function getNext()
    {
        return $this->links['next'];
    }
    
    public function getLast()
    {
        return $this->links['last'];
    }
}