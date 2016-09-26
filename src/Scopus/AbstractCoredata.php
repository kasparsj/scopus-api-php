<?php

namespace Scopus;

class AbstractCoredata
{
    /** @var array */
    protected $data;
    
    public function __construct(array $data)
    {
        $this->data = $data;
    }
}