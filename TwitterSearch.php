<?php

namespace Maalls;

class TwitterSearch extends TwitterApi {
  

  public function search($query) {

    $params = array(
        "q" => urlencode($query),
        "count" => 100,
        "result_type" => "recent");
    
    $results = array();

    do {

        $json = $this->get("search/tweets", $params);

        $response = json_decode($json, true);
        
        if(!isset($response["statuses"])) {

            throw new \Exception($json);

        }

        $tweets = $response["statuses"];

        if($tweets) {
        
            if(isset($params["max_id"])) {

                array_shift($tweets);

            }            

            if($tweets) {
                
                $params["max_id"] = $tweets[count($tweets) - 1]["id_str"];
                $results = array_merge($results, $tweets);

            }

        }
           
    }
    while(count($tweets) > 0);

    return $results;

  }

}