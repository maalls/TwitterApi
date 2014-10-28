<?php

include __dir__ . "/../vendor/autoload.php";
class TwitterApiTest extends PHPUnit_Framework_TestCase {
  
  public function testConstruct() {

    try {
      
      $twitterApi = new Maalls\TwitterApi();
      $this->assertTrue(false, "Exception should be thrown.");

    }
    catch(Exception $e) {

      if($e->getMessage() == "oauth_access_token required.") $this->assertTrue(true);
      else $this->assertTrue(false, "Unexpected exception message.");

    }

    try {
      
      $twitterApi = new Maalls\TwitterApi("dsfdsffd");
      $this->assertTrue(false, "Exception should be thrown.");

    }
    catch(Exception $e) {

      if($e->getMessage() == "oauth_acess_token_secret required.") $this->assertTrue(true);
      else $this->assertTrue(false, "Unexpected exception message.");

    }

    try {
      
      $twitterApi = new Maalls\TwitterApi("dsfdsffd", "tdsfdsfdsf");
      $this->assertTrue(false, "Exception should be thrown.");

    }
    catch(Exception $e) {

      if($e->getMessage() == "consumer_key required.") $this->assertTrue(true);
      else $this->assertTrue(false, "Unexpected exception message.");

    }

    try {
      
      $twitterApi = new Maalls\TwitterApi("dsfdsffd", "tdsfdsfdsf", "dsfdsfsdfsdf");
      $this->assertTrue(false, "Exception should be thrown.");

    }
    catch(Exception $e) {

      if($e->getMessage() == "consumer_secret required.") $this->assertTrue(true);
      else $this->assertTrue(false, "Unexpected exception message.");

    }

  }

  public function testGet() {

    
    $twitterApi = $this->createTwitterApi();
    $json = $twitterApi->get("search/tweets", array("q" => "hello", "count" => 5));

    $data = json_decode($json, true);

    $this->assertEquals(count($data["statuses"]), 5, "get returns the expected number of results.");

  }

  public function testPost() {

    $twitterApi = $this->createTwitterApi();

    $data = json_decode($twitterApi->post("statuses/update", array("status" => "@usn_sns hello world" . time())), true);

    $this->assertTrue(isset($data["id_str"]));
    


  }

  protected function createTwitterApi() {

    $config = @parse_ini_file(__dir__ . "/config/config.ini");

    if(!$config) {

      $msg = "To do the test, create test/config/config.ini file that contains your Twitter credentials. (see test/config/config.ini.sample)";
      throw new Exception($msg);

    }

    return new Maalls\TwitterApi($config["oauth_access_token"], $config["oauth_access_token_secret"], $config["consumer_key"], $config["consumer_secret"]);


  }

}