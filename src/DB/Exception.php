<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 * @package Base
 * @subpackage DB
 */


namespace Aimeos\Base\DB;


/**
 * \Exception for database related operations.
 *
 * @package Base
 * @subpackage DB
 */
class Exception extends \Aimeos\Base\Exception
{
	protected $info;
	protected $state;


	/**
	 * Initializes the exception.
	 *
	 * @param string $message Error message
	 * @param string|int $state SQL error code
	 * @param string|array $info Additional error info
	 */
	public function __construct( string $message, $state = '', $info = '' )
	{
		parent::__construct( $message, is_numeric( $state ) ? (int) $state : 0 );

		$this->state = $state;
		$this->info = $info;
	}


	/**
	 * Returns the SQL error code.
	 *
	 * @return string SQL error code
	 */
	public function getSqlState()
	{
		return $this->state;
	}


	/**
	 * Returns the addtional error information.
	 *
	 * @return string Additional error info
	 */
	public function getInfo()
	{
		return $this->info;
	}
}
