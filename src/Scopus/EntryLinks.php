<?php

namespace Scopus;

class EntryLinks extends BaseLinks
{
    public function getAuthorAffiliation()
    {
        return $this->links['author-affiliation'];
    }
    
    public function getScopus()
    {
        return $this->links['scopus'];
    }
    
    public function getScopusCitedby()
    {
        return $this->links['scopus-citedby'];
    }
}