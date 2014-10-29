<?php

namespace Maalls\Test;

include __dir__ . "/bootstrap.php";

class TwitterSearchTest extends \PHPUnit_Framework_TestCase {
  
  public function testSearch() {

    $search = Factory::create("\Maalls\TwitterSearch");
    $r = $search->search("red bull is good");
    var_dump($r);

  }

}