<?php

namespace Scopus\Response;

class AbstractCitations
{
    /** @var array */
    protected $data;

    /** @var CiteInfo[] */
    protected $citeInfos;

    /** @var CiteCountHeader */
    protected $citeCountHeader;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getHindex()
    {
        return $this->data['h-index'];
    }

    private function getParentIdentifier()
    {
        return $this->data['identifier-legend']['identifier'][0] ?? null;
    }

    public function getIdentifier()
    {
        $parent = $this->getParentIdentifier();
        return $parent ? $parent["dc:identifier"] : null;
    }

    public function getDoi()
    {
        $parent = $this->getParentIdentifier();
        return $parent ? $parent["prism:doi"] : null;
    }

    public function getScopusId()
    {
        $parent = $this->getParentIdentifier();
        return $parent ? $parent["scopus_id"] : null;
    }

    public function getCiteInfos()
    {
        if (isset($this->data['citeInfoMatrix']['citeInfoMatrixXML']['citationMatrix']['citeInfo'])) {
            return $this->citeInfos ?: $this->citeInfos = array_map(function ($citeInfo) {
                return new CiteInfo($citeInfo);
            }, $this->data['citeInfoMatrix']['citeInfoMatrixXML']['citationMatrix']['citeInfo']);
        }
        return null;
    }

    public function getCiteCountHeader()
    {
        return isset($this->data['citeColumnTotalXML']['citeCountHeader']) ? new CiteCountHeader($this->data['citeColumnTotalXML']['citeCountHeader']) : null;
    }

    public function getCompactInfo()
    {
        $header = $this->getCiteCountHeader();
        if (!$header) return null;

        $clmHeader = $header->getColumnHeading();
        $clmCount = $header->getColumnTotal();

        if (!$clmHeader || !$clmCount) return null;

        $compactData = [];
        $compactData["citation_previous_dates"] = $header->getPrevColumnTotal();
        $compactData["citation_subsequent_dates"] = $header->getLaterColumnTotal();

        if (is_array($clmHeader) || is_object($clmHeader)) {
            foreach ($clmHeader as $index => $value)
                $compactData[$value["$"]] = $clmCount[$index]["$"];
        } else $compactData[$clmHeader] = $clmCount;

        return $compactData;
    }
}
