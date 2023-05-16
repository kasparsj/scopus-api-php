<?php

namespace Scopus\Response;

class AuthorSubjectArea
{
    /** @var array */
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getAbbrev()
    {
        return isset($this->data['abbrev']) ? $this->data['abbrev'] : null;
    }

    public function getCode()
    {
        return isset($this->data['code']) ? $this->data['code'] : null;
    }

    public function getArea()
    {
        return isset($this->data['area']) ? $this->data['area'] : null;
    }
}
