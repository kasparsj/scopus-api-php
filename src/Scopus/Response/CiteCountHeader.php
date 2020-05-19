<?php

namespace Scopus\Response;

class CiteCountHeader
{
    /** @var array */
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getPrevColumnHeader()
    {
        return $this->data["prevColumnHeader"];
    }

    public function getColumnHeading()
    {
        return $this->data["columnHeading"];
    }

    public function getLaterColumnHeading()
    {
        return $this->data["laterColumnHeading"];
    }

    public function getPrevColumnTotal()
    {
        return $this->data["prevColumnTotal"];
    }

    public function getColumnTotal()
    {
        return $this->data["columnTotal"];
    }

    public function getLaterColumnTotal()
    {
        return $this->data["laterColumnTotal"];
    }

    public function getRangeColumnTotal()
    {
        return $this->data["rangeColumnTotal"];
    }

    public function getGrandTotal()
    {
        return $this->data["grandTotal"];
    }
}
