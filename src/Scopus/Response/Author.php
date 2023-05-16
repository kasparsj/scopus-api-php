<?php

namespace Scopus\Response;

class Author
{
    /** @var array */
    protected $data;

    /** @var AuthorCoredata */
    protected $coreData;

    /** @var Affiliation */
    protected $affiliation;

    /** @var Affiliation[] */
    protected $affiliation_history;

    /** @var AuthorSubjectArea[] */
    protected $subject_areas;

    /** @var AuthorProfile */
    protected $profile;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getCoreData()
    {
        return $this->coreData ?: $this->coreData = new AuthorCoredata($this->data['coredata']);
    }

    public function getHindex()
    {
        return isset($this->data['h-index']) ? $this->data['h-index'] : null;
    }

    public function getCoAutorCount()
    {
        return isset($this->data['coauthor-count']) ? $this->data['coauthor-count'] : null;
    }

    //non funziona
    public function getAffiliation()
    {
        return $this->affiliation ?: $this->affiliation = new Affiliation($this->prepareAffiliationData($this->data['affiliation-current']));
    }

    public function getAffiliationHistory()
    {
        if (isset($this->data['affiliation-history'])) {
            return $this->affiliation_history ?: $this->affiliation_history = array_map(function ($affiliation) {
                return new Affiliation($this->prepareAffiliationData($affiliation));
            }, $this->data['affiliation-history']['affiliation']);
        }
    }

    public function getSubjectAreas()
    {
        if (isset($this->data['subject-areas'])) {
            return $this->subject_areas ?: $this->subject_areas = array_map(function ($area) {
                return new AuthorSubjectArea($this->prepareSubjectArea($area));
            }, $this->data['subject-areas']['subject-area']);
        }
    }

    public function getProfile()
    {
        if (isset($this->data['author-profile'])) {
            return $this->profile ?: $this->profile = new AuthorProfile($this->data['author-profile']);
        }
    }

    protected function prepareAffiliationData($affiliation)
    {
        return [
            'afid' => $affiliation['@id'],
            'affiliation-url' => $affiliation['@href']
        ];
    }

    protected function prepareSubjectArea($area)
    {
        return [
            'abbrev' => $area['@abbrev'],
            'code' => $area['@code'],
            'area' => $area['$']
        ];
    }
}
