<?php

namespace Scopus\Response;

interface IAuthor
{
    public function getId();

    public function getUrl();

    public function getGivenName();

    public function getSurname();

    public function getInitials();

    public function getIndexedName();
}