<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2023
 * @package Base
 * @subpackage View
 */


namespace Aimeos\Base\View\Helper\Access;


/**
 * View helper class for checking access levels
 *
 * @package Base
 * @subpackage View
 */
class Standard extends \Aimeos\Base\View\Helper\Base implements Iface
{
	private $groups;


	/**
	 * Initializes the view helper
	 *
	 * @param \Aimeos\Base\View\Iface $view View object
	 * @param \Closure|array $groups Group codes assigned to the current user or closure function that returns the list
	 */
	public function __construct( \Aimeos\Base\View\Iface $view, $groups = [] )
	{
		parent::__construct( $view );
		$this->groups = $groups;
	}


	/**
	 * Checks the access level of the current user
	 *
	 * @param string|array $groups Group names that are allowed
	 * @return bool True if access is allowed, false if not
	 */
	public function transform( $groups ) : bool
	{
		if( is_callable( $this->groups ) )
		{
			$fcn = $this->groups;
			$this->groups = $fcn();
		}

		return (bool) count( array_intersect( (array) $groups, (array) $this->groups ) );
	}
}
