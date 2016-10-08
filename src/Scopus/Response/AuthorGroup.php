<?php

namespace Scopus\Response;

class AuthorGroup
{
    /** @var array */
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }
}