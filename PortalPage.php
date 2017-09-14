<?php

class PortalPage {

  /**
   * Array of SearchItem class.
   *
   * @var array 
   */
  public $results;

  /**
   * Page number for paginated searches. Each page returns max of 24 results.
   *
   * @var number
   */
  public $currentPage;

  /**
   * Total rows property returned from JSON API.
   * 
   * @var string 
   */
  public $totalRows;

  /**
   * Set empty array of results to allow adding as batches.
   */
  function __construct() {
    $this->results = [];
    $this->currentPage = 0;
  }

  /**
   * Run search query.
   *
   * @var number $count Number of results to return.
   *
   * @return array Search results as SearchItem class.
   */
  public function search($count = 0) {
    $baseUrl = 'https://www.nasa.gov/api/1/query/ubernodes.json';
    while($count >= $this->getCount()) {
      $url = $baseUrl . "?page=" . $this->currentPage;
      echo "Fetching {$url} ... \n";
      $results = json_decode(file_get_contents($url));
      // Allows for multiple pages.
      $this->addEntries($results);
      $this->currentPage += 1;
    }
  }

  /**
   * Add search results.
   *
   * @param stdClass $data Converted from JSON from NASA API.
   *
   * @return void
   */
  function addEntries($data) {
    $this->results = array_merge($this->results, $this->processEntries($data->ubernodes));
    $this->totalRows = $data->meta->total_rows;
  }

  /**
   * Create array of search entries from JSON API search page.
   *
   * @param stClass $data Search result.
   *
   * @return array Array of SearchItem class.
   */
  function processEntries($data) {
    $entries;
    foreach($data as $item) {
      $entries[] = new SearchItem($item);
    }
    return $entries;
  }

  /**
   * Tally number of search items currently held.
   *
   * @return string Display count.
   */
  function getCount() {
    return count($this->results);
  }

  /**
   * Fetch full ubernode data for each item in search results.
   *
   * @return array Ubernode class items.
   */
  public function buildResults() {
    $ubernodes = [];
    foreach ($this->results as $item) {
      echo "Fetching full node {$item->nid} ... \n";
      $ubernodes[] = $item->fetchUbernode();
    }
    return $ubernodes;
  }

  /**
   * Display teasers for all results on page.
   *
   * @return string Output to console.
   */
  public function showPageResults() {
    $fullResults = $this->buildResults();
    foreach($fullResults as $item) {
      echo $item->displayTeaser() . "\n";
    }
    echo "{$this->getCount()} records of {$this->totalRows}. \n";
  }

  /**
   * Display search page on console.
   *
   * @return NULL
   */
  public function echoResults() {
    var_dump($this->results);
    echo "Returned {$this->getCount()} of total rows: " . $this->totalRows . "\n";
  }

}

class SearchItem {

  /**
   * Node ID of search item.
   *
   * @var number
   */
  public $nid;

  /**
   * TODO: convert to real PHP date item??
   *
   * @var string
   */
  public $date;

  function __construct($data) {
    $this->nid = $data->nid;
    $this->date = $data->promoDateTime;
  }

  /**
   * Create absolute path for Ubernode JSON record.
   *
   * @return 
   */
  function getUrl() {
    return "https://www.nasa.gov/api/1/record/node/" . $this->nid . ".json";
  }

  /**
   * Fetch full Ubernode data from search result.
   *
   * @return Ubernode class
   */
  function fetchUbernode() {
    echo "Fetching {$this->nid} ... \n";
    $json = json_decode(file_get_contents($this->getUrl()));
    $node = new Ubernode($json->ubernode, $json->images);
    return $node;
  }

}