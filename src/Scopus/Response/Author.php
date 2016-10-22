<?php

namespace Scopus\Response;

class Author
{
    /** @var array */
    protected $data;
    
    /** @var Affiliation */
    protected $affiliation;
    
    /** @var Affiliation[] */
    protected $affiliation_history;
    
    /** @var AuthorProfile */
    protected $profile;

    public function __construct(array $data)
    {
        $this->data = $data;
    }
    
    public function getAffiliation()
    {
        return $this->affiliation ?: $this->affiliation = new Affiliation($this->prepareAffiliationData($this->data['affiliation-current']));
    }
    
    public function getAffiliationHistory()
    {
        if (isset($this->data['affiliation-history'])) {
            return $this->affiliation_history ?: $this->affiliation_history = array_map(function($affiliation) {
                return new Affiliation($this->prepareAffiliationData($affiliation));
            }, $this->data['affiliation-history']);
        }
    }
    
    public function getProfile()
    {
        return $this->profile ?: $this->profile = new AuthorProfile($this->data['author-profile']);
    }
    
    protected function prepareAffiliationData($affiliation)
    {
        return [
            'afid' => $affiliation['@id'],
            'affiliation-url' => $affiliation['@href']
        ];
    }
    
}