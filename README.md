TwitterApi
==========

Simple Twitter API using OAuth 1.1, in PHP.

#Installation


##Using Composer
Add the follow to your composer.json:
```json
  "require": {
    "maalls/Curl": "1.0"
  }
```
Then run the following command line:
```bash
$composer update
```

#Examples
```php

use Maalls\TwitterApi;

// A get request.
$api = new TwitterApi($access_token, $access_token_secret, $consumer_key, $consumer_secret);
$json = $api->get('search/tweets', array('q' => "github"));

// A post request.
$json = $api->>post("statuses/update", array("status" => "I love coding."));

// iterate() works for actions that returns an array of tweets: it collects all the tweets available by making several HTTP request and adjusting max_id parameters.
// see https://dev.twitter.com/rest/public/timelines
$json = $api->iterate('twitter/search', array('q' => 'githun'));


```
