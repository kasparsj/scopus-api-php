<?php

namespace Scopus\Response;

class AbstractCitations
{
    /** @var array */
    protected $data;

    /** @var AbstractCoredata[] */
    protected $identifiers;

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

    public function getIdentifiers()
    {
        if (isset($this->data['identifier-legend']['identifier'])) {
            return $this->identifiers ?: $this->identifiers = array_map(function ($identifier) {
                return new AbstractCoredata($identifier);
            }, $this->data['identifier-legend']['identifier']);
        }
        return null;
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
        if (isset($this->data['citeColumnTotalXML']['citeCountHeader']))
            return $this->citeCountHeader ?? $this->citeCountHeader = new CiteCountHeader($this->data['citeColumnTotalXML']['citeCountHeader']);

        return null;
    }

    /**
     * Extracts the citations per year of each document
     * @return array [document_scopus_id => [year => value ]]
     */
    public function getCompactInfo()
    {
        $header = $this->getCiteCountHeader();
        if (!$header) return null;

        $clmHeader = $header->getColumnHeading();
        $citeInfos = $this->getCiteInfos();

        if (!$clmHeader || !$citeInfos) return null;
        if (!is_array($clmHeader)) $clmHeader = [$clmHeader];

        $compactData = [];
        foreach ($citeInfos as $citeInfo) {
            foreach ($clmHeader as $index => $value) {
                $compactData[$citeInfo->getScopusId()][$value["$"]] = $citeInfo->getColumnCount()[$index]["$"];
            }
        }
        return $compactData;
    }

    /**
     * Extracts total citations per year
     * @return array [year => value ]
     */
    public function getTotalCompactInfo()
    {
        $header = $this->getCiteCountHeader();
        if (!$header) return null;

        $clmHeader = $header->getColumnHeading();
        $clmCount = $header->getColumnTotal();

        if (!$clmHeader || !$clmCount) return null;
        if (!is_array($clmHeader)) $clmHeader = [$clmHeader];

        $compactData = [];
        $compactData["citation_previous_dates"] = $header->getPrevColumnTotal();
        $compactData["citation_subsequent_dates"] = $header->getLaterColumnTotal();

        foreach ($clmHeader as $index => $value)
            $compactData[$value["$"]] = $clmCount[$index]["$"];

        return $compactData;
    }
}
