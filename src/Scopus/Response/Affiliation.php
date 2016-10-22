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
    
    public function getUrl()
    {
        return isset($this->data['affiliation-url']) ? $this->data['affiliation-url'] : null;
    }
    
    public function getName()
    {
        return isset($this->data['affilname']) ? $this->data['affilname'] : null;
    }
    
    public function getCity()
    {
        return isset($this->data['affiliation-city']) ? $this->data['affiliation-city'] : null;
    }
    
    public function getCountry()
    {
        return isset($this->data['affiliation-country']) ? $this->data['affiliation-country'] : null;
    }
    
    public function getNameVariant()
    {
        if (isset($this->data['name-variant'])) {
            return $this->nameVariants ?: $this->nameVariants = array_map(function($nameVariant) {
                return $nameVariant['$'];
            }, $this->data['name-variant']);
        }
    }
}