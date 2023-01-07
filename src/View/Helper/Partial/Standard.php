<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 * @package Base
 * @subpackage View
 */


namespace Aimeos\Base\View\Helper\Partial;


/**
 * View helper class for rendering partials.
 *
 * @package Base
 * @subpackage View
 */
class Standard
	extends \Aimeos\Base\View\Helper\Base
	implements \Aimeos\Base\View\Helper\Partial\Iface
{
	/**
	 * Returns the rendered partial.
	 *
	 * @param string $file Relative path to the template
	 * @param array $params Associative list of key/value pair that should be available in the partial
	 * @return string Rendered partial content
	 */
	public function transform( string $file, array $params = [] ) : string
	{
		$view = clone $this->view();
		$view->assign( $params );

		return $view->render( $file );
	}
}
