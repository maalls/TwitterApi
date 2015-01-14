<?php

namespace Maalls;
use \Exception;

class TwitterApi extends OAuth1 {


  public function __construct($oauth_access_token = "", $oauth_access_token_secret = "", $oauth_consumer_key = "", $consumer_secret = "") {

    $this->setBaseUrl("https://api.twitter.com/1.1/");
    $this->setFormat("json");

    return parent::__construct($oauth_access_token, $oauth_access_token_secret, $oauth_consumer_key, $consumer_secret);


  }

  public function search($queries, $max_page = null) {

    $params = array("result_type" => "recent", "count" => 100);
    $queries = is_array($queries) ? $queries : array("q" => $queries);

    return $this->iterate("search/tweets", array_merge($params, $queries), "GET", $max_page);

  }

  public function iterate($action, $queries = array(), $method = "GET", $max_page = null) {


    $params = $queries;
    $results = array();
    $page = 1;
    $continue = true;

    do {

        $this->log($action . " " . http_build_query($params));
        $json = $this->get($action, $params, $method);

        if($this->getCurl()->getInfo(CURLINFO_HTTP_CODE) != 200) throw new \Exception("Invalid HTTP CODE: " . $this->getCurl()->getInfo(CURLINFO_HTTP_CODE) . " : " . $json);
        

        $tweets = json_decode($json, true);

        if(isset($tweets["statuses"])) {

          $tweets = $tweets["statuses"];

        }

        
        if($tweets) {
        
            if(isset($params["max_id"])) {

                array_shift($tweets);

            }            

            if($tweets) {
                
                $params["max_id"] = $tweets[count($tweets) - 1]["id_str"];
                $results = array_merge($results, $tweets);

            }

        }

        $this->log("$page count: " . count($tweets) . ", total: " . count($results) . ", rate limit: " . $this->header["x-rate-limit-remaining"] . " / " . $this->header["x-rate-limit-limit"]);
        $reset = $this->header["x-rate-limit-reset"];

        $this->log("Reset : " . date("Y-m-d H:i:s", $reset) . ", now : " . date("Y-m-d H:i:s"));

        

        if(count($tweets) == 0) {

            $this->log("No more tweets " . ($results ? $results[count($results) - 1]["id_str"] : ""));
            $continue = false;

        }
        elseif(isset($params["since_id"]) && $tweets[count($tweets) - 1]["id_str"] <= $params["since_id"]) {

            $this->log("last tweet has been reached.");
            $continue = false;

        }

        if($page  === $max_page) {

          $continue = false;

        }

        $page++;
           
    }
    while($continue);

    return $results;

  }


}
