<?php

namespace Scopus\Response;

class EntryAuthor extends AuthorName
{
    public function __construct(array $data)
    {
        parent::__construct(array_merge($data, [
            'indexed-name' => $data['authname']
        ]));
    }
    
    public function getId()
    {
        return $this->data['authid'];
    }
    
    public function getName()
    {
        return $this->data['authname'];
    }
    
    public function getAffiliationId()
    {
        return $this->data['afid'][0]['$'];
    }
    
    public function getUrl()
    {
        return $this->data['author-url'];
    }
}