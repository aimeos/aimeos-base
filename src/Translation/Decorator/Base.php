<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 * @package Base
 * @subpackage Translation
 */


namespace Aimeos\Base\Translation\Decorator;


/**
 * Base class for all translator decorators.
 *
 * @package Base
 * @subpackage Translation
 */
abstract class Base
	extends \Aimeos\Base\Translation\Base
	implements \Aimeos\Base\Translation\Decorator\Iface
{
	private \Aimeos\Base\Translation\Iface $object;


	/**
	 * Initializes the decorator.
	 *
	 * @param \Aimeos\Base\Translation\Iface $object Translation object or decorator
	 */
	public function __construct( \Aimeos\Base\Translation\Iface $object )
	{
		$this->object = $object;
	}


	/**
	 * Returns the translated string.
	 *
	 * @param string $domain Translation domain
	 * @param string $string String to be translated
	 * @return string The translated string
	 */
	public function dt( string $domain, string $string ) : string
	{
		return $this->object->dt( $domain, $string );
	}


	/**
	 * Returns the translated string by the given plural and quantity.
	 *
	 * @param string $domain Translation domain
	 * @param string $singular String in singular form
	 * @param string $plural String in plural form
	 * @param int $number Quantity to chose the correct plural form for languages with plural forms
	 * @return string Returns the translated singular or plural form of the string depending on the given number.
	 */
	public function dn( string $domain, string $singular, string $plural, int $number ) : string
	{
		return $this->object->dn( $domain, $singular, $plural, $number );
	}


	/**
	 * Returns all locale string of the given domain.
	 *
	 * @param string $domain Translation domain
	 * @return array Associative list with original string as key and translation
	 * 	as value or an associative list with index => translation as value if
	 * 	plural forms are available
	 */
	public function all( string $domain ) : array
	{
		return $this->object->all( $domain );
	}


	/**
	 * Returns the current locale string.
	 *
	 * @return string ISO locale string
	 */
	public function getLocale() : string
	{
		return $this->object->getLocale();
	}


	/**
	 * Returns the wrapped translation object.
	 *
	 * @return \Aimeos\Base\Translation\Iface Translation object
	 */
	protected function object() : \Aimeos\Base\Translation\Iface
	{
		return $this->object;
	}
}
