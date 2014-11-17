<?php

namespace Maalls\Test;

include __dir__ . "/bootstrap.php";

class OAuthHmacSha1Test extends \PHPUnit_Framework_TestCase {
  
  public function testGetSignature() {

    $oauth = new \Maalls\OAuthHmacSha1(
      "post", 
      "https://api.twitter.com/1/statuses/update.json",
      array(
        "include_entities" => "true", 
        "status" => "Hello Ladies + Gentlemen, a signed OAuth request!",
        "oauth_consumer_key" => "xvz1evFS4wEEPTGEFPHBog",
        "oauth_nonce" => "kYjzVBB8Y0ZFabxSWbWovY3uYSQ2pTgmZeNu2VS4cg",
        "oauth_signature_method" => "HMAC-SHA1",
        "oauth_timestamp" => 1318622958,
        "oauth_token" => "370773112-GmHxMAgYyLbNEtIKZeRNFsMKPR9EyMZeS9weJAEb",
        "oauth_version" => "1.0"),
      "kAcSOqF21Fu85e7zjz7ZN2U4ZRhfV3WpwPAoE3Z7kBw",
      "LswwdoUaIvS8ltyTt5jkRh4J50vUPVVHtR2YPi5kE");


    $this->assertEquals(
      "POST&https%3A%2F%2Fapi.twitter.com%2F1%2Fstatuses%2Fupdate.json&include_entities%3Dtrue%26oauth_consumer_key%3Dxvz1evFS4wEEPTGEFPHBog%26oauth_nonce%3DkYjzVBB8Y0ZFabxSWbWovY3uYSQ2pTgmZeNu2VS4cg%26oauth_signature_method%3DHMAC-SHA1%26oauth_timestamp%3D1318622958%26oauth_token%3D370773112-GmHxMAgYyLbNEtIKZeRNFsMKPR9EyMZeS9weJAEb%26oauth_version%3D1.0%26status%3DHello%2520Ladies%2520%252B%2520Gentlemen%252C%2520a%2520signed%2520OAuth%2520request%2521", 
      $oauth->getSignatureBaseString());

    $this->assertEquals(
      "kAcSOqF21Fu85e7zjz7ZN2U4ZRhfV3WpwPAoE3Z7kBw&LswwdoUaIvS8ltyTt5jkRh4J50vUPVVHtR2YPi5kE", 
      $oauth->getSigningKey());

    $this->assertEquals("tnnArxj06cWHq44gCs1OSKk/jLY=", $oauth->getSignature());



  }

}