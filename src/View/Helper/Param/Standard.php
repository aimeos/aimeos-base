<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 * @package Base
 * @subpackage View
 */


namespace Aimeos\Base\View\Helper\Param;


/**
 * View helper class for retrieving parameter values.
 *
 * @package Base
 * @subpackage View
 */
class Standard
	extends \Aimeos\Base\View\Helper\Base
	implements \Aimeos\Base\View\Helper\Param\Iface
{
	private array $params;


	/**
	 * Initializes the parameter view helper.
	 *
	 * @param \Aimeos\Base\View\Iface $view View instance with registered view helpers
	 * @param array $params Associative list of key/value pairs
	 */
	public function __construct( \Aimeos\Base\View\Iface $view, array $params = [] )
	{
		parent::__construct( $view );

		$this->params = $params;
	}


	/**
	 * Returns the parameter value.
	 *
	 * @param string|null $name Name of the parameter key or null for all parameters
	 * @param mixed $default Default value if parameter key is not available
	 * @param bool $escape Escape HTML if single parameter is returned
	 * @return mixed Parameter value or associative list of key/value pairs
	 */
	public function transform( string $name = null, $default = null, bool $escape = true )
	{
		if( $name === null ) {
			return $this->params;
		}

		$parts = explode( '/', trim( $name, '/' ) );
		$param = $this->params;

		foreach( $parts as $part )
		{
			if( isset( $param[$part] ) ) {
				$param = $param[$part];
			} else {
				return $default;
			}
		}

		if( is_array( $param ) || $escape === false ) {
			return $param;
		}

		return \Aimeos\Base\Str::html( (string) $param, ENT_NOQUOTES );
	}
}
