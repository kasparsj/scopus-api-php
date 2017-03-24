<?php

use Scopus\Response\Entry;
use Scopus\ScopusApi;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$autoloader = require './vendor/autoload.php';

function print_memory() {
    gc_collect_cycles();
    echo round(memory_get_usage() / 1024 / 1024, 2) . 'MB ';
}

function process_entry(ScopusApi $api, Entry $entry) {
    $abstractUrl = $entry->getLinks()->getSelf();
    $abstract = $api->retrieve($abstractUrl);
    var_dump($abstract);

    $authors = $entry->getAuthors();
    foreach ($authors as $author) {
        $authorUrl = $author->getUrl();
        $author = $api->retrieve($authorUrl);
        var_dump($author);
    }
}

function main() {
    $apiKey = "114ff0c1b77a0cc62e05efdedefd1e6f";
    $api = new ScopusApi($apiKey);
    $results = $api
        ->query("af-id(60071066)")
        ->start(0)
        ->count(25)
        ->viewComplete()
        ->search();
    var_dump($results);

    print_memory();

    foreach ($results->getEntries() as $entry) {
        process_entry($api, $entry);

        print_memory();
    }
}

print_memory();

main();

print_memory();
