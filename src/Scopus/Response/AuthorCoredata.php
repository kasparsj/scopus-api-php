<?php

namespace Scopus\Response;

class AuthorCoredata
{
    /** @var array */
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getUrl()
    {
        return $this->data['prism:url'];
    }

    public function getIdentifier()
    {
        return isset($this->data['dc:identifier']) ? $this->data['dc:identifier'] : null;
    }

    public function getAuthorID()
    {
        $identifier = $this->getIdentifier();
        if (substr($identifier, 0, 10) === 'AUTHOR_ID:') {
            return explode(':', $identifier, 2)[1];
        }
    }

    public function getEID()
    {
        return isset($this->data['eid']) ? $this->data['eid'] : null;
    }

    public function getDocumentCount()
    {
        return isset($this->data['document-count']) ? $this->data['document-count'] : null;
    }

    public function getCitedByCount()
    {
        return isset($this->data['cited-by-count']) ? $this->data['cited-by-count'] : null;
    }

    public function getCitationCount()
    {
        return isset($this->data['citation-count']) ? $this->data['citation-count'] : null;
    }
}
