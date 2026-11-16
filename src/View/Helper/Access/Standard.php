<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2026
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
	private \stdClass $resolved;


	/**
	 * Initializes the view helper
	 *
	 * The "super" group is ignored because super users must be granted access
	 * by the \Aimeos\Base\View\Helper\Access\All helper instead of an editable group.
	 * The closure is called only once, also if the view and its helpers are cloned.
	 *
	 * @param \Aimeos\Base\View\Iface $view View object
	 * @param \Closure|array $groups Group codes assigned to the current user or closure function that returns the list
	 */
	public function __construct( \Aimeos\Base\View\Iface $view, $groups = [] )
	{
		parent::__construct( $view );
		$this->groups = $groups;
		// shared with the clones of the helper, so they don't resolve the groups again
		$this->resolved = new \stdClass();
	}


	/**
	 * Checks the access level of the current user
	 *
	 * @param string|array $groups Group names that are allowed
	 * @return bool True if access is allowed, false if not
	 */
	public function transform( $groups ) : bool
	{
		if( !isset( $this->resolved->groups ) )
		{
			$list = is_callable( $this->groups ) ? ( $this->groups )() : $this->groups;
			$this->resolved->groups = array_diff( (array) $list, ['super'] );
		}

		return (bool) count( array_intersect( (array) $groups, $this->resolved->groups ) );
	}
}
