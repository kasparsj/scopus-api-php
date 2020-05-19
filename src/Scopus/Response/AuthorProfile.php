<?php

namespace Scopus\Response;

class AuthorProfile
{
    /** @var array */
    protected $data;

    /** @var AuthorName */
    protected $preferredName;

    /** @var AuthorName[] */
    protected $nameVariants;

    /** @var Journal[] */
    protected $journalHistory;

    /** @var AuthorAffiliation */
    protected $affiliationCurrent;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getDataCreated()
    {
        if (isset($this->data['date-created'])) {
            $dateCreat = $this->data['date-created'];
            return $dateCreat['@year'] . '-' . $dateCreat['@month'] . '-' . $dateCreat['@day'];
        }
        return null;
    }

    public function getPreferredName()
    {
        return $this->preferredName ?: $this->preferredName = new AuthorName($this->data['preferred-name']);
    }

    public function getNameVariants()
    {
        return $this->nameVariants ?: $this->nameVariants = array_map(function ($nameVariant) {
            return new AuthorName($nameVariant);
        }, isset($this->data['name-variant']) ?
            isset($this->data['name-variant']['indexed-name']) ? [$this->data['name-variant']] : $this->data['name-variant'] : []);
    }

    //getClassificationgroup 

    public function getPublicationRange()
    {
        if (isset($this->data['publication-range'])) {
            $pubRange = $this->data['publication-range'];
            return $pubRange['@start'] . '-' . $pubRange['@end'];
        }
        return null;
    }

    //to try
    public function getJournalHistory()
    {
        if (isset($this->data['journal-history'])) {
            return $this->journalHistory ?: $this->journalHistory = array_map(function ($journal) {
                return new Journal($this->prepareJournalHistory($journal));
            }, $this->data['journal-history']);
        }
    }

    protected function prepareJournalHistory($journal)
    {
        return [
            'type' => $journal['@type']
        ];
    }

    public function getAffiliationCurrent()
    {
        $affiliation = (array_key_exists(0, $this->data['affiliation-current']['affiliation'])) ? $this->data['affiliation-current']['affiliation'][0] : $this->data['affiliation-current']['affiliation'];
        return $this->affiliationCurrent ?: $this->affiliationCurrent = new AuthorAffiliation($affiliation);
    }


    //getAffiliatonHistory
}
