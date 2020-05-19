# Scopus API for PHP (Unofficial)

PHP SDK for [Scopus APIs](https://dev.elsevier.com/scopus.html)

Currently supported APIs:
- Scopus Search API
- Abstract Retrieval API
- Author Retrieval API
- Affiliation Retrieval API
    - Search Author API
    - Citation Overview API

Original Project: [https://github.com/kasparsj/scopus-api-php](https://github.com/kasparsj/scopus-api-php)

Custom Project: [https://github.com/AntoninoBonanno/scopus-api-php](https://github.com/AntoninoBonanno/scopus-api-php)

## Installation

`composer require kasparsj/scopus-search-api` (Original project)

Overwrite this project to the original project if you want to use my updates

## Usage:

```php
use Scopus\ScopusApi;

// replace with your API key
$apiKey = "114ff0c3b57a0ec62e15efdedefd2e6f";
$api = new ScopusApi($apiKey);

// Scopus Search API
$results = $api
    ->query("af-id(60071066)")
    ->start(0)
    ->count(5)
    ->viewComplete()
    ->search();

var_dump($results);

foreach ($results->getEntries() as $entry) {
    $abstractUrl = $entry->getLinks()->getSelf();
    
    // Abstract Retrieval API
    $abstract = $api->retrieve($abstractUrl);
    
    var_dump($abstract);

    $authors = $entry->getAuthors();
    foreach ($authors as $author) {
        $authorUrl = $author->getUrl();
        
        // Author Retrieval API
        $author = $api->retrieve($authorUrl);
        
        var_dump($author);
    }
}
```

## API Docs

https://kasparsj.github.io/scopus-api-php/

## My contribution
* Added Search Author API
* Added Citation Overview API
* Created a support function to retrieve Document of specific Author easly
* Updated classes

[Bonanno Antonino](https://github.com/AntoninoBonanno)