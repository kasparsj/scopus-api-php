<?php

namespace Scopus\Response;

class Journal
{
    /** @var array */
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getType()
    {
        return isset($this->data['type']) ? $this->data['type'] : null;
    }

    public function getSourceTitle()
    {
        return isset($this->data['sourcetitle']) ? $this->data['sourcetitle'] : null;
    }

    public function getSourceTitleAbbrev()
    {
        return isset($this->data['sourcetitle-abbrev']) ? $this->data['sourcetitle-abbrev'] : null;
    }

    public function getIssn()
    {
        return isset($this->data['issn']) ? $this->data['issn'] : null;
    }
}
