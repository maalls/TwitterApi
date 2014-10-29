TwitterApi
==========

Simple Twitter API using OAuth 1.1, in PHP.

```php

use Maalls\TwitterApi;

...

$api = new TwitterApi($access_token, $access_token_secret, $consumer_key, $consumer_secret);
$json = $api->get('search/tweets', array('q' => "github"));
```
