<?php

namespace Scopus\Response;

class BibrecordHead
{
    /** @var array */
    protected $data;
    
    /** @var Correspondence */
    protected $correspondence;

    public function __construct(array $data)
    {
        $this->data = $data;
    }
    
    public function getCorrespondence()
    {
        return $this->correspondence ?: $this->correspondence = new Correspondence($this->data['correspondence']);
    }
}