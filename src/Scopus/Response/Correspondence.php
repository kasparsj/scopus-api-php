<?php

namespace Scopus\Response;

class Correspondence
{
    /** @var array */
    protected $data;
    
    /** @var */
    protected $person;

    public function __construct(array $data)
    {
        $this->data = $data;
    }
    
    public function getPerson()
    {
        return $this->person ?: $this->person = new CorrespondencePerson($this->data['person']);
    }
}