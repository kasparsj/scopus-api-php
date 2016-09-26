<?php

namespace Scopus\Response;

class Abstracts
{
    /** @var array */
    protected $data;
    
    /** @var AbstractCoredata */
    protected $coredata;
    
    public function __construct(array $data)
    {
        $this->data = $data;
    }
    
    public function getCoredata()
    {
        return $this->coredata ?: $this->coredata = new AbstractCoredata($this->data['coredata']);
    }
}