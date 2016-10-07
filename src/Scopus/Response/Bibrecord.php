<?php

namespace Scopus\Response;

class Bibrecord
{
    /** @var array */
    protected $data;
    
    /** @var BibrecordHead */
    protected $head;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getHead()
    {
        return $this->head ?: $this->head = new BibrecordHead($this->data['head']);
    }
}