<?php

namespace Scopus\Response;

class AbstractItem
{
    /** @var array */
    protected $data;
    
    /** @var Bibrecord */
    protected $bibrecord;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getBibrecord()
    {
        return $this->bibrecord ?: $this->bibrecord = new Bibrecord($this->data['bibrecord']);
    }
}