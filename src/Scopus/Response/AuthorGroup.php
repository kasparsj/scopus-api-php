<?php

namespace Scopus\Response;

class AuthorGroup
{
    /** @var array */
    protected $data;

    /** @var AbstractAuthor[] */
    protected $authors;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return AbstractAuthor[]
     */
    public function getAuthors()
    {
        if (isset($this->data['author'])) {
            return $this->authors ?: $this->authors = array_map(function($author) {
                return new AbstractAuthor($author);
            }, $this->data['author']);
        }
    }

    /**
     * @param $name
     * @return AbstractAuthor
     */
    public function findAuthorByName($name)
    {
        $matches = array_filter($this->getAuthors(), function(AbstractAuthor $author) use ($name) {
            return $author->getIndexedName() === $name;
        });
        if ($matches) {
            return array_values($matches)[0];
        }
    }
}