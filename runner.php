<?php

require "./PortalFetcher.php";
require "./PortalPage.php";
require "./Ubernode.php";
require 'vendor/autoload.php';

use GuzzleHttp\Client;

// Commands to run search.
$page = new PortalPage();
$page->search(128);

$fullNodes = $page->buildResults();
foreach($fullNodes as $node) {
  if ($node->hasLocal()) {
    echo "\n found the node \n";
  } else {
    $node->saveToLocal();
    echo "\n new node added \n";
  }
}
