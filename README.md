# Scopus API for PHP (Unofficial)

PHP SDK for [Scopus APIs](https://dev.elsevier.com/scopus.html)

Currently, supported APIs:
- Scopus Search API
- Abstract Retrieval API
- Author Retrieval API
- Affiliation Retrieval API
    - Search Author API
    - Citation Overview API

## Installation

`composer require kasparsj/scopus-search-api` 

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

## Changelog
- 14/11/2021 - v1.2
  *  Bug fix
- 19/05/2020 - v1.1
  * Added Search Author API
  * Added Citation Overview API
  * Created a support function to retrieve Document of specific Author easly
  * Updated classes

[Bonanno Antonino](https://github.com/AntoninoBonanno)