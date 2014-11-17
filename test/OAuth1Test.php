<?php

namespace Maalls\Test;

include __dir__ . "/bootstrap.php";

class OAuth1Test extends \PHPUnit_Framework_TestCase {
  
  public function testRequest() {

    $oauth = new \Maalls\OAuth1("", "", "xvz1evFS4wEEPTGEFPHBog");

    $oauth->request();

  }

}