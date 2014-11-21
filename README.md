TwitterApi
==========

Simple Twitter API using OAuth 1.1, in PHP.

#Installation using Composer

Add the following line into the require section of your composer.json:
```json
  "require": {
    "maalls/TwitterApi": "~1.0"
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

The class also collect the HTTP response header into an array, this is useful because it includes rate limit information:
```php
var_dump($api->header);

array(17) {
  [...]
  'x-rate-limit-limit' =>
  string(3) "180"
  'x-rate-limit-remaining' =>
  string(3) "179"
  'x-rate-limit-reset' =>
  string(10) "1416472112"
  [...]
}
```

