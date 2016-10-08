<?php

namespace Scopus\Response;

interface IAbstract
{
    /**
     * @return IAuthor[]|null
     */
    public function getAuthors();
}