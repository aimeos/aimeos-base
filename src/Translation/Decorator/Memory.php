<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 * @package Base
 * @subpackage Translation
 */


namespace Aimeos\Base\Translation\Decorator;


/**
 * Memory caching decorator for translation classes.
 *
 * @package Base
 * @subpackage Translation
 */
class Memory
	extends \Aimeos\Base\Translation\Decorator\Base
	implements \Aimeos\Base\Translation\Decorator\Iface
{
	private array $translations;


	/**
	 * Initializes the decorator.
	 *
	 * @param \Aimeos\Base\Translation\Iface $object Translation object or decorator
	 * @param string[] $translations Associative list of domains and singular
	 * 	strings as key and list of translation number and translations as value:
	 * 	array( <domain> => array( <singular> => array( <index> => <translations> ) ) )
	 */
	public function __construct( \Aimeos\Base\Translation\Iface $object, array $translations = [] )
	{
		parent::__construct( $object );
		$this->translations = $translations;
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
		return $this->translations[$domain][$string][0] ?? parent::dt( $domain, $string );
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
		$index = $this->getPluralIndex( $number, $this->getLocale() );
		return $this->translations[$domain][$singular][$index] ?? parent::dn( $domain, $singular, $plural, $number );
	}
}
