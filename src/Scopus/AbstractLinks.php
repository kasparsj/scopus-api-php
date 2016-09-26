<?php

namespace Scopus;

abstract class AbstractLinks
{
    /** @var array */
    protected $links;

    public function __construct(array $links)
    {
        $this->links = [];
        foreach ($links as $link) {
            $this->links[$link['@ref']] = $links['@href'];
        }
    }

    public function getSelf()
    {
        return $this->links['self'];
    }
}