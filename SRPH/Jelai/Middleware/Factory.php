<?php namespace SRPH\Jelai\Middleware;

use Closure;
use OutOfBoundsException;
use InvalidArgumentException;

class Factory {

	/**
	 * Middlewares to be ran
	 *
	 * @var array
	 */
	protected $interceptors = array();

	/**
	 * Adds an interceptor to our middleware
	 *
	 * @see $this->throwNumericInterceptor
	 * @param string $key Name of the filter (for accessibility)
	 * @param Closure|object $callback The function to be executed
	 */
	public function add($key, $callback)
	{
		// If a filter of the given key/name already exists,
		// let's throw an error so no unexpected behavior
		// occurs.
		if ( array_key_exists($key, $this->interceptors) )
		{
			throw new Exception('Filter already exists');
		}

		// The interceptor accessor must never be a numeric key
		// because it has no sense; and it may cause unexpected behavior.
		if ( is_numeric($key) )
		{
			$this->throwNumericInterceptor();
		}

		$this->interceptors[$key] = $callback;
	}

	/**
	 * Run(s) the provided interceptor(s)
	 *
	 * @see $this->runInterceptor
	 * @param mixed $interceptor
	 * @return void
	 */
	public function run($interceptor)
	{
		// If the provided interceptor is an array, we just
		// run each interceptor in order. Otherwise, we just run the given key.
		if ( is_array($interceptor) )
		{
			foreach($interceptor as $key)
			{
				$this->runInterceptor($key);
			}
		}


		else
		{
			$this->runInterceptor($interceptor);
		}
	}

	/**
	 * Runs the interceptor
	 *
	 * @see $this->throwNumericInterceptor
	 * @throws InvalidArgumentException When a numeric value is passed
	 * @throws OutOfBoundsException When interceptor does not exist
	 * @param string $interceptor Interceptor to be ran
	 * @return void
	 */
	protected function runInterceptor($interceptor)
	{
		// We don't like to run numeric values, because it doesn't make sense.
		// We provide this exception in-case the developer tries to do so.
		if ( is_numeric($interceptor) )
		{
			$this->throwNumericInterceptor();
		}

		// We'll throw an exception in-case the provided interceptor is non-existent.
		if ( !array_key_exists($interceptor, $this->interceptors) )
		{
			throw new OutOfBoundsException('Invalid middlware interceptor.');
		}

		// We no longer need the provided $interceptor key, so
		// we assign the `$interceptor` variable to the callback or
		// the value of the given key.
		$interceptor = $this->interceptors[$interceptor];

		// We execute the handler accordingly. We either call the `handle`
		// method, or instantiate and call the said method, or the execute
		// the interceptor if a Closure.
		if ( $interceptor instanceof Closure)
		{
			$interceptor();
		}
		else if ( is_object($interceptor) )
		{
			$interceptor->handle();
		}
		elseif ( is_string($interceptor) )
		{
			(new $interceptor)->handle();
		}
	}

	/**
	 * Let's throw an error about the provided interceptor being
	 * a numeric value instead of a string
	 *
	 * @throws InvalidArgumentException
	 * @return void
	 */
	protected function throwNumericInterceptor()
	{
		throw new InvalidArgumentException('Provided interceptor must be a string, not a numeric value.');
	}

}
