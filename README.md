TwitterApi
==========

Simple PHP Twitter API class using OAuth 1.1.

```php

use Maalls\TwitterApi;

...

$api = new TwitterApi($access_token, $access_token_secret, $consumer_key, $consumer_secret);
$json = $api->get('search/tweets', array('q' => "github"));
```
