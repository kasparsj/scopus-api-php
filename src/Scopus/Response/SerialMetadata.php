<?php

namespace Scopus\Response;

class SerialMetaData
{
    /** @var array */
    protected $data;

    /** @var EntrySerialTitle[] */
    protected $entries;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return EntrySerialTitle[]
     */
    public function getEntries()
    {
        if (isset($this->data['entry'])) {
            return $this->entries ?: $this->entries = array_map(function ($entry) {
                return new EntrySerialTitle($entry);
            }, $this->data['entry']);
        }
    }

    public function countEntries()
    {
        return isset($this->data['entry']) ? count($this->data['entry']) : 0;
    }
}
