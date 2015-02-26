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

		$this->interceptors[$key] = $callback instanceof Closure
			? $callback
			: ( (is_object($callback) ? $callback : new $callback )->handle() );
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
			throw new InvalidArgumentException('Provided interceptor must be a string, not a numeric value.');
		}

		// We'll throw an exception in-case the provided interceptor is non-existent.
		if ( !array_key_exists($interceptor, $this->interceptors) )
		{
			throw new OutOfBoundsException('Invalid middlware interceptor.');
		}

		// Yolo pls swag.
		$this->interceptors[$interceptor]();
	}

}
