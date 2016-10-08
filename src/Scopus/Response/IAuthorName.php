<?php

namespace Scopus\Response;

interface IAuthorName
{
    public function getGivenName();

    public function getSurname();

    public function getInitials();

    public function getIndexedName();
}