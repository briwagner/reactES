<?php

// UbernodeFetch interface
// PortalFetcher class 

interface UbernodeFetch {
  public function getNodeFromId($id);
}

class PortalFetcher implements UbernodeFetch {
  public $currentPage;

  /**
   * Fetch full Ubernode data for ID.
   *
   * @param number $id ID of ubernode record.
   *
   * @return Ubernode class.
   */
  public function getNodeFromId($id) {
    $baseUrl = "https://www.nasa.gov/api/1/record/node/";
    $nodeUrl = $baseUrl . $id . ".json";
    $node = json_decode(file_get_contents($nodeUrl));
    return new Ubernode($node->ubernode, $node->images);
  }

  /**
   * Fetch search results.
   *
   * @param number $count Number of records to retrieve.
   *
   * @return PortalPage class.
   */
  public function getResults($count = 0) {
    $page = new PortalPage();
    while ($count >= $page->getCount()) {
      // Set query string as needed, for paginated.
      // $query = $this->getQuery($count, $page->getCount());

      $results = $this->getSearchPage();
      $page->addEntries($results);
    }
    return $page;
  }

  /**
   * Get search results from NASA JSON API.
   *
   * @param string $query Optional query string to apply to URL.
   *
   * @return stdClass Converted JSON from API.
   */
  public function getSearchPage($query = NULL) {
    $baseUrl = 'https://www.nasa.gov/api/1/query/ubernodes.json' . $query;
    echo "Querying {$baseUrl} ... \n";
    $results = json_decode(file_get_contents($baseUrl));
    return $results;
  }

  /**
   * Create query string for search pages. Each search page has max of 24 items.
   *
   * @param number $count Number of records requested.
   *
   * @param number $currentCount Tally of records obtained so far.
   *
   * @return string Query string to append to base search URL.
   */
  public function getQuery($count, $currentCount) {
    if ($currentCount < 24) {
      return "page=0";
    } else {
      $currentPage = (int) ($currentCount / 24);
      $nextPage = $currentPage + 1;
      echo "Next page for query is {$nextPage} ... \n";
      return "?page=" . $nextPage;
    }
  }

}