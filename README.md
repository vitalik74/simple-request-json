# Simple class implement request with JSON body (simple-request-json)

Example with use:
```
$response = (new Request(['url' => 'Url ', 'postData' => 'post data']))->getResponse();
```

Or

```
$response = new Request(['url' => 'Url ', 'postData' => 'post data']);
$response = $response-> getResponse();
```

Or

```
$response = new Request();
$response->url = 'url';
$response->postData = 'post data';
$response->method = 'GET';// default in POST
$response = $response-> getResponse();
```

