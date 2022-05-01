<?php

namespace Scopus\Response;

class CitationCountLinks
{
    /**
     * @var array
     */
    protected $links;

    public function __construct(array $links)
    {
        $this->links = [];

        foreach ($links as $link) {
            $this->links[$link['@rel']] = [
                'href' => $link['@href'],
                'fa' => $link['@_fa'],
            ];
        }
    }
}
