<?php

include __dir__ . "/test/bootstrap.php";

$logger = new Logger();
$search = Maalls\Test\Factory::create("Maalls\TwitterSearch");
$search->setLogger($logger);

$curl = new Maalls\Curl\CurlCached();
$curl->setCacheDirectory(__dir__ . "/data");
$curl->setCacheDuration(3600);
//$curl->setLogger($logger);
$search->setCurl($curl);

$queries = array("q" => "nike");

if($latest = @file_get_contents(__dir__ . "/latest.txt")) {

  $queries["since_id"] = $latest - 1;

}

$rsp = $search->search($queries);

if($rsp) {

  $new = $rsp[0];

  echo "latest : " . $new["created_at"] . " " . $new["id_str"] . PHP_EOL;
  $old = $rsp[count($rsp) - 1];
  //echo "oldest : " . $old["created_at"] . " " . $old["id_str"] . PHP_EOL;

  $count = count($rsp);

  $seconds = strtotime($new["created_at"]) - strtotime($old["created_at"]);

  echo round($count / ($seconds / 3600)) . " tweets/h." . PHP_EOL;

  file_put_contents(__dir__ . "/latest.txt", $new["id_str"]);

}


class Logger {

  public function log($msg, $level = "info") {

    echo $msg . PHP_EOL;

  }

}