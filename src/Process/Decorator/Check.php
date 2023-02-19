<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2023
 * @package Base
 * @subpackage Process
 */


namespace Aimeos\Base\Process\Decorator;


/**
 * Check avaiability of parallel processing
 *
 * If not available, execute the tasks one after another
 *
 * @package Base
 * @subpackage Process
 */
class Check implements Iface
{
	private \Aimeos\Base\Process\Iface $object;


	/**
	 * Initializes the object
	 *
	 * @param \Aimeos\Base\Process\Iface $object Parallel processing object
	 */
	public function __construct( \Aimeos\Base\Process\Iface $object )
	{
		$this->object = $object;
	}


	/**
	 * Checks if processing tasks in parallel is available
	 *
	 * @return bool True if available, false if not
	 */
	public function isAvailable() : bool
	{
		return $this->object->isAvailable();
	}


	/**
	 * Starts a new task by executing the given anonymous function
	 *
	 * @param \Closure $fcn Anonymous function to execute
	 * @param array $data List of parameters that is passed to the closure function
	 * @param bool $restart True if the task should be restarted if it fails (only once)
	 * @return \Aimeos\Base\Process\Iface Self object for method chaining
	 * @throws \Aimeos\Base\Process\Exception If starting the new task failed
	 */
	public function start( \Closure $fcn, array $data, bool $restart = false ) : \Aimeos\Base\Process\Iface
	{
		if( $this->object->isAvailable() === true ) {
			$this->object->start( $fcn, $data, $restart );
		} else {
			call_user_func_array( $fcn, $data );
		}

		return $this;
	}


	/**
	 * Waits for the running tasks until all have finished
	 *
	 * @return \Aimeos\Base\Process\Iface Self object for method chaining
	 */
	public function wait() : \Aimeos\Base\Process\Iface
	{
		if( $this->object->isAvailable() === true ) {
			$this->object->wait();
		}

		return $this;
	}
}
