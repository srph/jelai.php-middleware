<?php namespace SRPH\Jelai\Middleware;

interface MiddlewareInterface {

	/**
	 * The middleware handle (closure) to be executed
	 *
	 * @return void
	 */
	public function handle();
	
}
