<?php

namespace Scopus\Response;

class CorrespondencePerson extends AuthorName 
{
    public function __construct(array $data)
    {
        parent::__construct([
            'initials' => $data['ce:initials'],
            'indexed-name' => $data['ce:indexed-name'],
            'surname' => $data['ce:surname'],
        ]);
    }
}