<?php

use GuzzleHttp\Client;

/**
 * Ubernode Class.
 *
 * UbernodeImage Class.
 */

class Ubernode {
  public $title;
  public $nid;
  public $promoDate;
  public $editDate;
  public $uri;
  public $teaser;
  public $body;
  public $images;
  public $rawData;

  /**
   * 
   *
   * @param string $data Json object from api for ubernode only.
   *
   * @param string $images Array of JSON objects from api for ubernode images. 
   */
  function __construct($data, $images) {
    $this->title     = $data->title;
    $this->nid       = $data->nid;
    $this->promoDate = $data->promoDateTime;
    $this->editDate  = $data->changed;
    $this->uri       = $this->absoluteUri($data->uri);
    $this->teaser    = property_exists($data, 'prLeaderSentence') ? $data->prLeaderSentence : '';
    $this->body      = property_exists($data, 'body') ? $data->body : '';
    $this->images    = $this->getImages($images);
    $this->rawData   = $this->convertJSON($data, $images);
  }

  /**
   * Print a brief list of node properties.
   *
   * @return string Output of various properties.
   */
  function displayTeaser() {
    return $this->nid . ": " . $this->title . "\n" . 
           "Published: " . $this->promoDate . "\n" . 
           $this->uri . "\n";
           $this->images[0];
  }

  /**
   * Convert relative path to absolute.
   *
   * @param string $relativePath Path string.
   *
   * @return string Absolute path.
   */
  function absoluteUri($relativePath) {
    $base = "https://www.nasa.gov";
    return $base . $relativePath;
  }

  /**
   * Build UbernodeImage objects from source JSON.
   *
   * @param stdClass $data Raw data from JSON API.
   *
   * @return array Array of UbernodeImage objects.
   */
  function getImages($data) {
    $images = [];
    foreach($data as $img) {
      $images[] = new UbernodeImage($img);
    }
    return $images;
  }

  /**
   * Check if local resource exists with given node id on local Elasticsearch.
   *
   * @param string $nid Node id.
   *
   * @return boolean Exists on local ElasticSearch or not.
   */
  function hasLocal() {
    $nid = $this->nid;
    $client = new Client();
    try {
      $request = $client->get('http://localhost:9200/ubernodes/ubernode/' . $nid);
      return $request->getStatusCode();
    } catch (\Exception $e){
      $req = $e->getResponse();
      return FALSE;
    }
  }

  /**
   * Save node to local Elasticsearch.
   *
   * @return boolean Saved or not.
   */
  function saveToLocal() {
    $data = $this->rawData;
    $client = new Client();
    try {
      $client->post(
        'http://localhost:9200/ubernodes/ubernode/' . $this->nid,
        ['body' => json_encode($data)]
      );
    } catch (\Exception $e) {
      return FALSE;
    }
  }

  /**
   * Convert JSON api format (node, images) into single object with images property.
   *
   * @param string $node Node object from json API.
   * 
   * @param string $images Array of image objects from json API.
   *
   */
  function convertJSON($node, $images) {
    $node->images = $images;
    return $node;
  }

}

class UbernodeImage {
  public $fid;
  public $uri;
  public $filemime;
  public $filesize;
  public $alt;
  public $title;
  public $width;
  public $height;
  public $sizes;

  function __construct($data) {
    $this->fid      = $data->fid;
    $this->uri      = $data->uri;
    $this->filemime = $data->filemime;
    $this->filesize = $data->filesize;
    $this->alt      = $data->alt;
    $this->title    = $data->title;
    $this->width    = $data->width;
    $this->height   = $data->height;
    $this->sizes    = $this->getImagePaths($data);
  }

  /**
   * Returns static array of pre-defined file path properties.
   *
   * @return array Static list.
   */
  function filePaths() {
    return [
      "crop1x1",
      "crop2x1",
      "crop2x2",
      "crop1x2",
      "crop4x3ratio",
      "cropHumongo",
      "cropBanner",
      "cropUnHoriz", 
      "cropUnVert",
      "fullWidthFeature",
      "lrThumbnail",
    ];
  }

  /**
   * Filter data props for file paths and make absolute paths.
   *
   * @param $data stdClass Image data from JSON from NASA API.
   *
   * @return array Assoc array of absolute paths for image versions.
   */
  function getImagePaths($data) {
    $base = "https://www.nasa.gov";
    // Convert object to array for filtering.
    $dataArray = (array) $data;
    // Filter for file paths only, based on pre-defined keys.
    $imagePaths = $this->filterForImagePaths($dataArray);
    // Make absolute paths from relative.
    return array_map(function($y) use ($base) {
      return $base . $y;
      },
    $imagePaths);
  }

  /**
   * Filter source object for filepaths.
   * 
   * @param array $arr Array of object properties from JSON.
   * 
   * @return array Assoc array of image file paths from source, i.e. relative paths.
   */
  function filterForImagePaths($arr) {
    $filePathKeys = $this->filePaths();
    $imagePaths = array_filter($arr, 
      function($x) use ($filePathKeys) {
       return in_array($x, $filePathKeys);
      },
      ARRAY_FILTER_USE_KEY
    );
    return $imagePaths;
  }

  /**
   * Get single filepath for specified image size.
   *
   * @param string $size Requested image size.
   *
   * @return string Filepath for specified size.
   */
  function getSize($size) {
    return $this->sizes[$size];
  }

}