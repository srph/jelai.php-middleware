# jelai.php-middleware

jelai.php Middleware abstraction. While it is called a Middleware, this is only a very simple abstraction for something similar to *Middlewares*. If you prefer, you can call it *Filters* instead!

## What is jelai.php?

Please see this [gist](https://gist.github.com/srph/2e2d51d46dadfdbc38e3).

## Usage

First, instantiate the middleware.

```php
require __DIR__ . '/path/to/src/SRPH/Jelai/Middleware/Factory.php';
$middleware = new SRPH\Jelai\Middleware\Factory;
```

We can only *add* an *interceptor* and *run* an *interceptor*.

**What is an interceptor**?

Well, you can describe it a callback that may halt the request (by sending it to another page, maybe?).

```php
function unauthorizedNotAllowed()
{
	if ( !$loggedIn )
	{
		header('Location: index.php');
		die();
	}
}
```


## API

### ```add``` (*```string```* ```$key```, *```mixed```* $callback)

Add an interceptor.

This example *redirects* the user to ```index.php``` if the *user* is a *guest*. It does not run the interceptor yet, but only add it to the list so it is ```run```nable later on.

```php
$auth = new MyAuthManager;
$request = new MyRequestManager;

$middleware->add('auth', function() use ($auth, $request) {
	if ( $auth->guest() )
	{
		return $request->to('index.php');
	}
});
```

##### Throws

- ```InvalidArgumentException```. If the provided interceptor is not a string.

### ```run``` (*```key```* ```$key```)

Used to run an interceptor.

```php
$middleware->run('auth');
```

#### Throws

- ```InvalidArgumentException```. If the provided interceptor is not a string.
- ```OutOfBoundsException```. If the provided interceptor does not exist.

## Acknowledgement

**jelai.php-middleware** © 2015+, Kier Borromeo (srph). **jelai.php** is released under the [MIT](mit-license.org) license.

> [srph.github.io](http://srph.github.io) &nbsp;&middot;&nbsp;
> GitHub [@srph](https://github.com/srph) &nbsp;&middot;&nbsp;
> Twitter [@_srph](https://twitter.com/_srph)
