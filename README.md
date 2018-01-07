# PSR-7 HTTP Authentication ACL Middleware

This middleware provides a very simple way to implement ACL in a PSR-7 environment.

It has been tested  with [Slim Framework](http://www.slimframework.com/) using the [PSR-7 Basic Auth Middleware] provided by tuupola (https://github.com/tuupola/slim-basic-auth).

## Install

Install latest version using [composer](https://getcomposer.org/).

```
$ composer require rcs_us/slim-middleware-basic-acl
```

## Usage

The middleware accepts two possible parameters. 

1. An array of one or more usernames allowed to access the route. This compares against the value that is ultimately stored in $_SERVER["PHP_AUTH_USER"] | $request->getServerParams()["PHP_AUTH_USER"] .
2. A callable that can do more complicated comparisons on $_SERVER["PHP_AUTH_USER"] | $request->getServerParams()["PHP_AUTH_USER"] .

```php
$app = new \Slim\App;
// .... implement HTTP Authentication , IE $app->add(new \Slim\Middleware\HttpBasicAuthentication( ... 

// array of usernames
$app->get("/path/of/your/route", "\App\Controller\SomeController:methodToCall")->add(new \Slim\Middleware\HttpBasicACL(["username1", "username2"]));

// callable
$app->get("/path/of/your/route", "\App\Controller\SomeController:methodToCall")->add(new \Slim\Middleware\HttpBasicACL(function ($request, $response) {
    // Some Logic Here Based On $_SERVER["PHP_AUTH_USER"] | $request->getServerParams()["PHP_AUTH_USER"] .
    // Returning false will trigger a 403, true will execute route
    return false;
}));

```

