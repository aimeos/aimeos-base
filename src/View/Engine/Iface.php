<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2022
 * @package Base
 * @subpackage View
 */


namespace Aimeos\Base\View\Engine;


/**
 * Common interface for all view engine classes
 *
 * @package Base
 * @subpackage View
 */
interface Iface
{
	/**
	 * Renders the output based on the given template file name and the key/value pairs
	 *
	 * @param \Aimeos\Base\View\Iface $view View object
	 * @param string $filename File name of the view template
	 * @param array $values Associative list of key/value pairs
	 * @return string|null Output generated by the template or null for none
	 * @throws \Aimeos\Base\View\Exception If the template couldn't be rendered
	 */
	public function render( \Aimeos\Base\View\Iface $view, string $filename, array $values ) : ?string;
}