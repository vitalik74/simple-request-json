# Simple class implement request with JSON body (simple-request-json)


REQUIREMENTS
------------

The minimum requirement by this application template that your Web server supports PHP 5.1.0.


INSTALLATION
------------

### Install via Composer

If you do not have [Composer](http://getcomposer.org/), you may install it by following the instructions
at [getcomposer.org](http://getcomposer.org/doc/00-intro.md#installation-nix).

You can then install this widget. Put in your composer.json in require case:

```
"vitalik74/simple-request-json": "*"
```
And run command "composer update".

USE
---
### Example with use:
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

