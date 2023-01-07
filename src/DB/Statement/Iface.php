<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 * @package Base
 * @subpackage DB
 */


namespace Aimeos\Base\DB\Statement;


/**
 * Required methods for all database statement objects.
 *
 * @package Base
 * @subpackage DB
 */
interface Iface
{
	/**
	 * Binds a value to a parameter in the statement.
	 *
	 * @param int $position Position index of the placeholder
	 * @param mixed $value Value which should be bound to the placeholder
	 * @param int $type Type of given value defined in \Aimeos\Base\DB\Stmt\Base as constant
	 * @return \Aimeos\Base\DB\Statement\Iface Statement instance for method chaining
	 */
	public function bind( int $position, $value, int $type = \Aimeos\Base\DB\Statement\Base::PARAM_STR ) : \Aimeos\Base\DB\Statement\Iface;

	/**
	 * Executes the statement.
	 *
	 * @return \Aimeos\Base\DB\Result\Iface Result object
	 */
	public function execute() : \Aimeos\Base\DB\Result\Iface;
}
