<?php

namespace Scopus\Response;

class AbstractCoredata
{
    /** @var array */
    protected $data;
    
    public function __construct(array $data)
    {
        $this->data = $data;
    }
}