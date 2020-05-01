<?php

namespace Scopus;

class SearchQuery
{
    const VIEW_STANDARD = 'STANDARD';
    const VIEW_COMPLETE = 'COMPLETE';

    protected $searchApi;

    protected $start;

    protected $count;

    protected $query;

    protected $view;

    protected $cursor;

    public function __construct(ScopusApi $searchApi, $query)
    {
        $this->searchApi = $searchApi;
        $this->query = $query;
        $this->start = 0;
        $this->count = 25;
        $this->view = self::VIEW_STANDARD;
        $this->cursor = null;
    }

    public function getStart()
    {
        return $this->start;
    }

    public function start($startIndex)
    {
        $this->start = $startIndex;
        return $this;
    }

    public function getCount()
    {
        return $this->count;
    }

    public function count($count)
    {
        $this->count = $count;
        return $this;
    }

    public function getQuery()
    {
        return $this->query;
    }

    public function query($query)
    {
        $this->query = $query;
        return $this;
    }

    public function withCursor()
    {
        $this->cursor = "*";
        return $this;
    }

    public function setCursor($cursor)
    {
        $this->cursor = $cursor;
        return $this;
    }

    public function getCursor()
    {
        return $this->cursor;
    }

    public function getView()
    {
        return $this->view;
    }

    public function viewStandard()
    {
        $this->view = self::VIEW_STANDARD;
        return $this;
    }

    public function viewComplete()
    {
        $this->view = self::VIEW_COMPLETE;
        return $this;
    }

    /**
     * @return Response\SearchResults
     */
    public function search()
    {
        return $this->searchApi->search($this->toArray());
    }

    public function toArray()
    {
        return [
            'query' => $this->query,
            'start' => $this->start,
            'count' => $this->count,
            'view' => $this->view,
            'cursor' => $this->cursor
        ];
    }
}
