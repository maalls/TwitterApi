TwitterApi
==========

Simple Twitter API using OAuth 1.1, in PHP.

#Installation

```json
Using composer
{
  "name": "maalls/twitterapi",
  "description": "Implementation of the Twitter API for PHP.",
  "type": "library",
  "keywords": ["twitter", "PHP", "API"],
  "homepage": "https://github.com/maalls/twitterapi",
  "license": "MIT License",
  "authors": [
    {
      "name": "Malo Yamakado",
      "homepage": "https://github.com/maalls"
    }
  ],
  "require": {

    "maalls/Curl": "1.0"

  },
  "autoload": {
    "files": ["OAuthHmacSha1.php", "OAuth1.php", "TwitterApi.php"]
  }
}
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
