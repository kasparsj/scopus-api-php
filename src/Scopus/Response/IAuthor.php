<?php

namespace Scopus\Response;

interface IAuthor extends IAuthorName
{
    public function getId();

    public function getUrl();
}