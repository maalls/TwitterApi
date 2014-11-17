<?php

namespace Maalls;

class OAuthHmacSha1 {
  
  private $http_method;
  private $base_url;
  private $parameters;
  private $consumer_secret;
  private $oauth_token_secret;

  public function __construct($http_method, $base_url, $parameters, $consumer_secret, $oauth_token_secret) {

    $this->http_method = $http_method;
    $this->base_url = $base_url;
    $this->parameters = $parameters;
    $this->consumer_secret = $consumer_secret;
    $this->oauth_token_secret = $oauth_token_secret;

  }

  public function getSignature() {

    $signature_base_string = $this->getSignatureBaseString();
    $signing_key = $this->getSigningKey();

    $oauth_signature = base64_encode(hash_hmac('sha1', $signature_base_string, $signing_key, true));

    return $oauth_signature;   


  }

  public function getSignatureBaseString() {

    $percent_encoded_parameters = array();

    foreach($this->parameters as $key => $value) {

      $percent_encoded_parameters[] = rawurlencode($key) . "=" . rawurlencode($value);

    }

    sort($percent_encoded_parameters);
    $parameters_string = implode("&", $percent_encoded_parameters);

    $signature_base_string_parameters = array();
    $signature_base_string_parameters[] = strtoupper($this->http_method);
    $signature_base_string_parameters[] = rawurlencode($this->base_url);
    $signature_base_string_parameters[] = rawurlencode($parameters_string);
    $signature_base_string = implode("&", $signature_base_string_parameters);

    return $signature_base_string;


  }

  public function getSigningKey() {

    return rawurlencode($this->consumer_secret) . "&" . rawurlencode($this->oauth_token_secret);

  }


}