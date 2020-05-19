<?php

namespace Scopus\Response;

class AuthorAffiliation
{
    /** @var array */
    protected $data;

    /** @var array */
    protected $nameVariants;

    public function __construct(array $data)
    {
        $this->data = $data['ip-doc'];
    }

    public function getId()
    {
        return $this->data['@id'];
    }

    public function getType()
    {
        return isset($this->data['@type']) ? $this->data['@type'] : null;
    }

    public function getDomain()
    {
        return isset($this->data['org-domain']) ? $this->data['org-domain'] : null;
    }

    public function getURL()
    {
        return isset($this->data['org-URL']) ? $this->data['org-URL'] : null;
    }

    public function getDispName()
    {
        return isset($this->data['afdispname']) ? $this->data['afdispname'] : null;
    }

    public function getPreferredName()
    {
        return isset($this->data['preferred-name']['$']) ? $this->data['preferred-name']['$'] : null;
    }

    public function getParentPreferredName()
    {
        return isset($this->data['parent-preferred-name']['$']) ? $this->data['parent-preferred-name']['$'] : null;
    }

    public function getSortName()
    {
        return isset($this->data['sort-name']) ? $this->data['sort-name'] : null;
    }

    public function getAddress()
    {
        return isset($this->data['address']['address-part']) ? $this->data['address']['address-part'] : null;
    }

    public function getCity()
    {
        return isset($this->data['address']['city']) ? $this->data['address']['city'] : null;
    }

    public function getCodeCity()
    {
        return isset($this->data['address']['state']) ? $this->data['address']['state'] : null;
    }

    public function getCountry()
    {
        return isset($this->data['address']['country']) ? $this->data['address']['country'] : null;
    }

    public function getPostalCode()
    {
        return isset($this->data['address']['postal-code']) ? $this->data['address']['postal-code'] : null;
    }

    public function getGeneralName()
    {
        return $this->getDispName() . " - " . $this->getCity() . ", " . $this->getCountry();
    }
}
