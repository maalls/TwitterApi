<?php

namespace Maalls;

class TwitterSearch extends TwitterApi {
  
  public $params = array();
  public $results = array();

  public function search($query) {

    $this->params = array(
        "count" => 100,
        "result_type" => "recent");
    
    if(is_array($query)) $this->params = array_merge($query, $this->params);
    else $this->params["q"] = $query;

    $this->results = array();

    $page = 1;
    $continue = true;

    do {

        $this->log("Searching " . http_build_query($this->params));
        $json = $this->get("search/tweets", $this->params);

        $response = json_decode($json, true);
        
        if(!isset($response["statuses"])) {

            throw new \Exception($json);

        }

        $tweets = $response["statuses"];

        if($tweets) {
        
            if(isset($this->params["max_id"])) {

                array_shift($tweets);

            }            

            if($tweets) {
                
                $this->params["max_id"] = $tweets[count($tweets) - 1]["id_str"];
                $this->results = array_merge($this->results, $tweets);

            }

        }

        $this->log("$page count: " . count($tweets) . ", total: " . count($this->results) . ", rate limit: " . $this->header["x-rate-limit-remaining"] . " / " . $this->header["x-rate-limit-limit"]);
        $reset = $this->header["x-rate-limit-reset"];

        $this->log("Reset : " . date("Y-m-d H:i:s", $reset) . ", now : " . date("Y-m-d H:i:s"));

        $page++;

        if(count($tweets) == 0) {

            $this->log("No more tweets " . $this->results[count($this->results) - 1]["id_str"]);
            $continue = false;

        }
        elseif(isset($this->params["since_id"]) && $tweets[count($tweets) - 1]["id_str"] > $this->params["since_id"]) {

            $this->log("last tweet has been reached.");
            $continue = false;

        }
           
    }
    while($continue);

    return $this->results;

  }

}