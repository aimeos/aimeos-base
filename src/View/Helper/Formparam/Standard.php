<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 * @package Base
 * @subpackage View
 */


namespace Aimeos\Base\View\Helper\Formparam;


/**
 * View helper class for generating form parameter names.
 *
 * @package Base
 * @subpackage View
 */
class Standard
	extends \Aimeos\Base\View\Helper\Base
	implements \Aimeos\Base\View\Helper\Formparam\Iface
{
	private array $names;


	/**
	 * Initializes the URL view helper.
	 *
	 * @param \Aimeos\Base\View\Iface $view View instance with registered view helpers
	 * @param string[] $names Prefix names when generating form parameters (will be "name1[name2][name3]..." )
	 */
	public function __construct( \Aimeos\Base\View\Iface $view, array $names = [] )
	{
		parent::__construct( $view );

		$this->names = $names;
	}


	/**
	 * Returns the name of the form parameter.
	 * The result is a string that allows parameters to be passed as arrays if
	 * this is necessary, e.g. "name1[name2][name3]..."
	 *
	 * @param string|array $names Name or list of names
	 * @param bool $prefix TRUE to use available prefix, FALSE for names without prefix
	 * @return string Form parameter name
	 */
	public function transform( $names, bool $prefix = true ) : string
	{
		$names = array_merge( $prefix ? $this->names : [], (array) $names );

		if( ( $result = array_shift( $names ) ) === null ) {
			return '';
		}

		foreach( $names as $name ) {
			$result .= '[' . $name . ']';
		}

		return $result;
	}
}
