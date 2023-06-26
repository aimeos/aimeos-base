<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2019-2023
 * @package Base
 * @subpackage View
 */


namespace Aimeos\Base\View\Helper\Link;


/**
 * View helper class for building URLs in a simple way
 *
 * @package Base
 * @subpackage View
 */
class Standard
	extends \Aimeos\Base\View\Helper\Base
	implements \Aimeos\Base\View\Helper\Link\Iface
{
	/**
	 * Returns the URL for the given parameter
	 *
	 * @param string $cfgkey Prefix of the configuration key for the URL settings
	 * @param array $params Associative list of parameters that should be part of the URL
	 * @param array $config Associated list of additional configuration
	 * @param string[] $fragments Trailing URL fragment that are not relevant to identify the resource
	 * @return string Complete URL that can be used in the template
	 */
	public function transform( string $cfgkey, array $params = [], $config = [], array $fragments = [] ) : string
	{
		$view = $this->view();
		$cntl = $action = null;

		if( count( $parts = explode( '/', $cfgkey ) ) > 4 )
		{
			$list = array_slice( $parts, 2 );
			$cntl = array_shift( $list );
			$action = array_shift( $list );
		}

		$target = $view->config( $cfgkey . '/target' );
		$cntl = $view->config( $cfgkey . '/controller', $cntl ? ucfirst( $cntl ) : null );
		$action = $view->config( $cfgkey . '/action', $action );
		$config = array_replace( $view->config( $cfgkey . '/config', [] ), $config );
		$filter = $view->config( $cfgkey . '/filter', [] );

		$params = array_diff_key( $params, array_flip( $filter ) );

		return $view->url( $target, $cntl, $action, $params, $fragments, $config );
	}
}
