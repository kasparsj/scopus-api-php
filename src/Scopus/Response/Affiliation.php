<?php

namespace Scopus\Response;

class Affiliation
{
    /** @var array */
    protected $data;
    
    /** @var array */
    protected $nameVariants;
    
    public function __construct(array $data)
    {
        $this->data = $data;
    }
    
    public function getId()
    {
        return $this->data['afid'];
    }
    
    public function getName()
    {
        return $this->data['affilname'];
    }
    
    public function getCity()
    {
        return $this->data['affiliation-city'];
    }
    
    public function getCountry()
    {
        return $this->data['affiliation-country'];
    }
    
    public function getNameVariant()
    {
        return $this->nameVariants ?: $this->nameVariants = array_map(function($nameVariant) {
            return $nameVariant['$'];
        }, $this->data['name-variant']);
    }
}