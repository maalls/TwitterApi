<?php

namespace Maalls\Test;
use \Exception;
include __dir__ . "/bootstrap.php";

class TwitterApiTest extends \PHPUnit_Framework_TestCase {
  
  public function testConstruct() {

    try {
      
      $twitterApi = new \Maalls\TwitterApi();
      $this->assertTrue(false, "Exception should be thrown.");

    }
    catch(Exception $e) {

      if($e->getMessage() == "oauth_access_token required.") $this->assertTrue(true);
      else $this->assertTrue(false, "Unexpected exception message.");

    }

    try {
      
      $twitterApi = new \Maalls\TwitterApi("dsfdsffd");
      $this->assertTrue(false, "Exception should be thrown.");

    }
    catch(Exception $e) {

      if($e->getMessage() == "oauth_access_token_secret required.") $this->assertTrue(true);
      else $this->assertTrue(false, "Unexpected exception message.");

    }

    try {
      
      $twitterApi = new \Maalls\TwitterApi("dsfdsffd", "tdsfdsfdsf");
      $this->assertTrue(false, "Exception should be thrown.");

    }
    catch(Exception $e) {

      if($e->getMessage() == "oauth_consumer_key required.") $this->assertTrue(true);
      else $this->assertTrue(false, "Unexpected exception message.");

    }

    try {
      
      $twitterApi = new \Maalls\TwitterApi("dsfdsffd", "tdsfdsfdsf", "dsfdsfsdfsdf");
      $this->assertTrue(false, "Exception should be thrown.");

    }
    catch(Exception $e) {

      if($e->getMessage() == "consumer_secret required.") $this->assertTrue(true);
      else $this->assertTrue(false, "Unexpected exception message.");

    }

  }

  public function testGet() {

    $twitterApi = Factory::create("\Maalls\TwitterApi");
    $json = $twitterApi->get("search/tweets", array("q" => "hello world", "count" => 5));

    $data = json_decode($json, true);

    $this->assertEquals(count($data["statuses"]), 5, "get returns the expected number of results.");

  }

  public function testPost() {

    $twitterApi = Factory::create("\Maalls\TwitterApi");

    $data = json_decode($twitterApi->post("statuses/update", array("status" => "@usn_sns hello world " . time())), true);

    $this->assertTrue(isset($data["id_str"]));
    


  }

  public function testIterate() {

    $twitterApi = Factory::create("\Maalls\TwitterApi");

    $results = $twitterApi->iterate("/search/tweets", array("q" => "nike", "count" => 5), "get", 3);


    $this->assertEquals(13, count($results)); // 5 + 4 + 4 = 13

  }

  public function testSearch() {

    $twitterApi = Factory::create("\Maalls\TwitterApi");

    $results = $twitterApi->search(array("q" => "nike", "count" => 20), 3);


    $this->assertEquals(58, count($results)); // 20 + 19 + 19 = 58


  }

}