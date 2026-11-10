<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2026
 * @package Base
 * @subpackage View
 */


namespace Aimeos\Base\View\Helper\Idn;


/**
 * View helper class for displaying internationalized domain names of e-mail addresses
 *
 * @package Base
 * @subpackage View
 */
interface Iface extends \Aimeos\Base\View\Helper\Iface
{
	/**
	 * Returns the e-mail address with the domain in its Unicode form
	 *
	 * @param string|null $email E-mail address with the domain as punycode, e.g. "info@xn--mller-kva.de"
	 * @return string E-mail address with the domain in its Unicode form, e.g. "info@müller.de"
	 */
	public function transform( ?string $email ) : string;
}
