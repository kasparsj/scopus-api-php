<?php

namespace Scopus\Response;

class CorrespondencePerson extends AuthorName 
{
    public function __construct(array $data)
    {
        parent::__construct($data, 'ce');
    }
}