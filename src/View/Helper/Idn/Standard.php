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
class Standard
	extends \Aimeos\Base\View\Helper\Base
	implements \Aimeos\Base\View\Helper\Idn\Iface
{
	/**
	 * Returns the e-mail address with the domain in its Unicode form
	 *
	 * @param string|null $email E-mail address with the domain as punycode, e.g. "info@xn--mller-kva.de"
	 * @return string E-mail address with the domain in its Unicode form, e.g. "info@müller.de"
	 */
	public function transform( ?string $email ) : string
	{
		$email = (string) $email;

		if( ( $pos = strrpos( $email, '@' ) ) === false || stripos( $email, 'xn--', $pos ) === false ) {
			return $email;
		}

		// nontransitional processing (IDNA 2008) to keep e.g. "ß" instead of converting it to "ss"
		$domain = idn_to_utf8( substr( $email, $pos + 1 ), IDNA_NONTRANSITIONAL_TO_UNICODE, INTL_IDNA_VARIANT_UTS46 );

		return $domain !== false ? substr( $email, 0, $pos + 1 ) . $domain : $email;
	}
}
