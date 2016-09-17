# scopus-search-api-php
Very unofficial Scopus Search API for PHP

## Usage:

```
$apiKey = "11111111111111111111111111111111";
$api = new SearchApi($apiKey);
$results = $api
  ->query([
    'af-id' => 11111111
  ])
  ->start(0)
  ->count(25)
  ->viewComplete()
  ->search();
var_dump($results);
```
