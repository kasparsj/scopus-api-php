<?php

namespace Scopus\Response;

class Abstracts
{
    /** @var array */
    protected $data;
    
    /** @var AbstractCoredata */
    protected $coredata;
    
    /** @var AbstractItem */
    protected $item;
    
    public function __construct(array $data)
    {
        $this->data = $data;
    }
    
    public function getCoredata()
    {
        return $this->coredata ?: $this->coredata = new AbstractCoredata($this->data['coredata']);
    }
    
    public function getItem()
    {
        return $this->item ?: $this->item = new AbstractItem($this->data['item']);
    }
}