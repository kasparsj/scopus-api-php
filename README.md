# Scopus API for PHP (Unofficial)

PHP SDK for [Scopus APIs](https://dev.elsevier.com/scopus.html)

Currently supported APIs:
- Scopus Search API
- Abstract Retrieval API
- Author Retrieval API
- Affiliation Retrieval API

## Installation

`composer require kasparsj/scopus-search-api`

## Usage:

```php
use Scopus\ScopusApi;

// replace with your API key
$apiKey = "114ff0c3b57a0ec62e15efdedefd2e6f";
$api = new ScopusApi($apiKey);
$results = $api
    ->query("af-id(60071066)")
    ->start(0)
    ->count(5)
    ->viewComplete()
    ->search();

var_dump($results);

foreach ($results->getEntries() as $entry) {
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
```

## API Docs

https://kasparsj.github.io/scopus-api-php/
