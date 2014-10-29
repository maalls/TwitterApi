<?php

namespace Maalls\Test;
use \Maalls\TwitterApi;
use \Maalls\TwitterSearch;

class Factory {
  
  
  public static function create($class) {

    $config = @parse_ini_file(__dir__ . "/config/config.ini");

    if(!$config) {

      $msg = "To do the test, create test/config/config.ini file that contains your Twitter credentials. (see test/config/config.ini.sample)";
      throw new Exception($msg);

    }

    return new $class($config["oauth_access_token"], $config["oauth_access_token_secret"], $config["consumer_key"], $config["consumer_secret"]);


  }

}