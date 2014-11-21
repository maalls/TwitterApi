<?php
namespace Maalls;
use \Exception;

class OAuth1 {
  

  private $base_url = "";
  private $format = "json";
  private $oauth_access_token;
  private $oauth_access_token_secret;
  private $oauth_consumer_key;
  private $consumer_secret;

  private $curl;
  private $logger;

  private $oauth;
  private $action;
  private $method;

  public $header;
  public $body;


  public function __construct($oauth_access_token = "", $oauth_access_token_secret = "", $oauth_consumer_key = "", $consumer_secret = "") {

    
    if(!$oauth_access_token) throw new Exception("oauth_access_token required.");
    if(!$oauth_access_token_secret) throw new Exception("oauth_access_token_secret required.");
    if(!$oauth_consumer_key) throw new Exception("oauth_consumer_key required.");
    if(!$consumer_secret) throw new Exception("consumer_secret required.");
    
    $this->oauth_access_token = $oauth_access_token;
    $this->oauth_access_token_secret = $oauth_access_token_secret;
    $this->oauth_consumer_key = $oauth_consumer_key;
    $this->consumer_secret = $consumer_secret;

    $this->curl = new Curl\Curl();

  }

  public function get($action, $queries = array()) {

    return $this->request($action, $queries, "GET");

  }

  public function post($action, $queries = array()) {

    return $this->request($action, $queries, "POST");

  }

  public function request($action, $queries, $method = "GET") {

    $this->action = $action;
    $this->method = $method;
    $this->queries = $queries;
    
    $response = $this->executeRequest();
    $this->processResponse($response);

    return $this->body;


  }

  public function executeRequest() {

    $this->initRequest();

    return $this->curl->execute();

  }

  public function initRequest() {

    if(!$this->getBaseUrl()) throw new Exception("Base URL required.");

    $options = array( 
        CURLOPT_URL => $this->getBaseUrl() . ($this->queries && $this->method == "GET" ? "?" . $this->buildQuery($this->queries) : ""),
        CURLOPT_HTTPHEADER => array($this->getAuthorizationHeader()),
        CURLOPT_HEADER => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
    );

    if($this->queries && $this->method == "POST") {

      if(isset($this->queries["status"]) && $this->queries["status"][0] == "@") $this->queries["status"] = sprintf("\0%s", $this->queries['status']);

      $options[CURLOPT_POSTFIELDS] = $this->queries;

    }

    $this->curl->setOptions($options);


  }

  public function getAuthorizationHeader() {

    $header = 'Authorization: OAuth ';

    $values = array();
    foreach($this->createOAuthParameters() as $key => $value) $values[] = "$key=\"" . rawurlencode($value) . "\"";
    $header .= implode(', ', $values);

    return $header;

  }

  public function createOAuthParameters() {

    $oauth = array( 
        'oauth_consumer_key' => $this->oauth_consumer_key,
        'oauth_nonce' => time(),
        'oauth_signature_method' => 'HMAC-SHA1',
        'oauth_token' => $this->oauth_access_token,
        'oauth_timestamp' => time(),
        'oauth_version' => '1.0'
    );

    $oauthHmacSha1 = new oauthHmacSha1(
      $this->method, 
      $this->getBaseUrl(), 
      $this->method == "GET" ? array_merge($oauth, $this->queries) : $oauth, 
      $this->consumer_secret, 
      $this->oauth_access_token_secret);

    $oauth["oauth_signature"] = $oauthHmacSha1->getSignature();

    return $oauth;

  }


  public function processResponse($response) {

    $header_size = $this->curl->getInfo(CURLINFO_HEADER_SIZE);
    $header = substr($response, 0, $header_size);

    $this->header = array();
    foreach(explode("\n", $header) as $k => $line) {

      if($k == 0 || !trim($line)) continue;

      $kval = explode(":", $line);
      if(count($kval) == 2) $this->header[trim($kval[0])] = trim($kval[1]);

    }

    $this->body = substr($response, $header_size);

  }

  public function setBaseUrl($base_url) {

    $this->base_url = $base_url;

  }

 

  public function getBaseUrl() {

    return $this->base_url . $this->action . ($this->format ? "." . $this->format : '');

  }

  public function setFormat($format) {

    $this->format = $format;

  }

  public function getFormat() {

    return $this->format;

  }

  public function setCurl($curl) {

    $this->curl = $curl;

  }

  public function getCurl() {

    return $this->curl;

  }

  public function buildQuery($queries) {

    $q = "";

    foreach($queries as $key => $value) $q[] = "$key=" . rawurlencode($value);

    return implode("&", $q);

  }

  public function setLogger($logger) {

    $this->logger = $logger;

  }

  public function log($msg, $level = "info") {

    if($this->logger) $this->logger->log($msg, $level);

  }


}