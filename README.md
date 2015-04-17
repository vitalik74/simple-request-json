# Simple class implement request with JSON body (simple-request-json)


REQUIREMENTS
------------

The minimum requirement by this application template that your Web server supports PHP 5.4.0.


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

Data request run from Curl in default. If Curl not installed in system data request run from `file_get_contents` function.

### Example, how to use:
```
$response = (new Request(['url' => 'Url ', 'postData' => 'post data']))->getResponse();
```

Or

```
$response = new Request(['url' => 'Url ', 'postData' => 'post data']);
$response = $response->getResponse();
```

Or

```
$response = new Request();
$response->url = 'url';
$response->postData = 'post data';
$response->method = 'GET';// default in POST
$response = $response->getResponse();
```


### Public property

```$postData``` - Post data to put in server.  

```$url``` - Url from server.

```$method``` - POST or GET method.

```$toArray``` - Convert response from server to array with json_decode function
