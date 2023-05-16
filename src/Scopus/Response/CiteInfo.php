<?php

namespace Scopus\Response;

class CiteInfo extends AbstractCoredata implements IAbstract
{
    /** @var array */
    protected $data;

    /** @var Affiliation[] */
    protected $affiliations;

    /** @var EntryAuthor[] */
    protected $authors;

    //Entry of SEARCH_AUTHOR_URI
    /** @var AuthorName[] */
    protected $preferredName;

    public function __construct(array $data)
    {
        parent::__construct($data);
    }

    //identifier is in AbstractCoredata

    //url is in AbstractCoredata

    //title is in AbstractCoredata

    /**
     * @return EntryAuthor[]|null
     */
    public function getAuthors()
    {
        if (isset($this->data['author'])) {
            return $this->authors ?: $this->authors = array_map(function ($author) {
                return new EntryAuthor($author);
            }, $this->data['author']);
        }
    }

    public function getCitationType()
    {
        return $this->data["$"];
    }

    public function getCitationTypeCode()
    {
        return $this->data["@code"];
    }

    public function getSortYear()
    {
        return $this->data["sort-year"];
    }

    public function getStartingPage()
    {
        return $this->data["prism:startingPage"];
    }

    public function getEndingPage()
    {
        return $this->data["prism:endingPage"];
    }

    //getPublicationName is in AbstractCoredata

    //issn is in AbstractCoredata

    public function getPreviousColumnCount()
    {
        return $this->data["pcc"];
    }

    public function getColumnCount()
    {
        return $this->data["cc"];
    }

    public function getLaterColumnCount()
    {
        return $this->data["lcc"];
    }

    public function getRangeCount()
    {
        return $this->data["rangeCount"];
    }

    public function getRowTotal()
    {
        return $this->data["rowTotal"];
    }
}
