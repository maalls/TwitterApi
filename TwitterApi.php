<?php

namespace Maalls;
use \Exception;
class TwitterApi {

  private $base_url = "https://api.twitter.com/1.1/";
  private $format = "json";
  private $oauth_access_token;
  private $oauth_acess_token_secret;
  private $consumer_key;
  private $consumer_secret;

  private $curl;
  private $logger;

  private $oauth;
  private $action;
  private $method;


  public function __construct($oauth_access_token = "", $oauth_acess_token_secret = "", $consumer_key = "", $consumer_secret = "") {

    
    if(!$oauth_access_token) throw new Exception("oauth_access_token required.");
    if(!$oauth_acess_token_secret) throw new Exception("oauth_acess_token_secret required.");
    if(!$consumer_key) throw new Exception("consumer_key required.");
    if(!$consumer_secret) throw new Exception("consumer_secret required.");
    
    $this->oauth_access_token = $oauth_access_token;
    $this->oauth_acess_token_secret = $oauth_acess_token_secret;
    $this->consumer_key = $consumer_key;
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

    $this->initParameters($action, $queries, $method);
    $this->initOAuth();
    $this->initCurl();
    return $this->curl->execute();

  }

  public function initParameters($action, $queries = array(), $method = "GET") {

    $this->action = $action;
    $this->method = $method;
    $this->queries = $queries;
    
  }

  public function initOAuth() {

    $this->oauth = array( 
        'oauth_consumer_key' => $this->consumer_key,
        'oauth_nonce' => time(),
        'oauth_signature_method' => 'HMAC-SHA1',
        'oauth_token' => $this->oauth_access_token,
        'oauth_timestamp' => time(),
        'oauth_version' => '1.0'
    );

    if($this->method == "GET") $this->oauth = array_merge($this->oauth, $this->queries);
    
    ksort($this->oauth);

    $data = $this->method . "&" . rawurlencode($this->buildUrl()) . "&" . rawurlencode($this->buildQuery($this->oauth));
    $key = rawurlencode($this->consumer_secret) . '&' . rawurlencode($this->oauth_acess_token_secret);

    $this->oauth["oauth_signature"] = base64_encode(hash_hmac('sha1', $data, $key, true));


  }

  public function initCurl() {

    $header = array($this->buildAuthorizationHeader(), "Except:");

    $options = array( 
        CURLOPT_URL => $this->buildUrl() . ($this->queries && $this->method == "GET" ? "?" . $this->buildQuery($this->queries) : ""),
        CURLOPT_HTTPHEADER => $header,
        CURLOPT_HEADER => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
    );

    if($this->queries && $this->method == "POST") {

      if(isset($this->queries["status"]) && $this->queries["status"][0] == "@") $this->queries["status"] = sprintf("\0%s", $this->queries['status']);

      $options[CURLOPT_POSTFIELDS] = $this->queries;

    }

    $this->curl->setOptions($options);

  }

  public function buildAuthorizationHeader() {

    $return = 'Authorization: OAuth ';
    $values = array();
    
    foreach($this->oauth as $key => $value) $values[] = "$key=\"" . rawurlencode($value) . "\"";
    
    $return .= implode(', ', $values);
    return $return;

  }

  public function buildUrl() {

    return $this->base_url . $this->action . "." . $this->format;

  }


  public function setLogger($logger) {

    $this->logger = $logger;

  }

  public function setCurl($curl) {

    $this->curl = $curl;

  }

  public function buildQuery($queries) {

    $q = "";

    foreach($queries as $key => $value) $q[] = "$key=" . rawurlencode($value);

    return implode("&", $q);

  }


}