# Laravel PJAX Middleware

Enable the use of PJAX in Laravel. 

## Installation

Install using composer:

```
$ composer require torann/laravel-pjax-middleware
```

You'll then need to run `composer install` to download it and have the autoloader updated.

### Setup

Once installed you need to append the middleware class within the Http kernel. Open up `app/Http/Kernel.php` and find the `$middleware` variable.

```php
protected $middleware = [

    Torann\Pjax\PjaxMiddleware::class,

]
```

#### How to use

This middleware will check, before outputting the http response, for the `X-PJAX`'s 
header in the request. If found, it will crawl the response to return the requested 
element defined by `X-PJAX-Container`'s header.

Works great with [flight-with-pjax](https://github.com/Torann/flight-with-pjax) and [jquery.pjax.js](https://github.com/defunkt/jquery-pjax).