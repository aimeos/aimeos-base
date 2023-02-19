<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2023
 * @package Base
 * @subpackage Translation
 */


namespace Aimeos\Base\Translation;


/**
 * Translation using Gettext library
 *
 * @package Base
 * @subpackage Translation
 */
class Gettext
	extends \Aimeos\Base\Translation\Base
	implements \Aimeos\Base\Translation\Iface
{
	private array $files = [];
	private array $sources;


	/**
	 * Initializes the translation object using Gettext
	 *
	 * @param array $sources Associative list of translation domains and lists of translation directories.
	 * @param string $locale ISO language name, like "en" or "en_US"
	 */
	public function __construct( array $sources, string $locale )
	{
		parent::__construct( $locale );

		$this->sources = $sources;
	}


	/**
	 * Returns the translated string for the given domain.
	 *
	 * @param string $domain Translation domain
	 * @param string $singular String to be translated
	 * @return string The translated string
	 * @throws \Aimeos\Base\Translation\Exception Throws exception on initialization of the translation
	 */
	public function dt( string $domain, string $singular ) : string
	{
		foreach( $this->getTranslations( $domain ) as $object )
		{
			if( ( $result = $object->get( $singular ) ) !== null )
			{
				if( is_array( $result ) && isset( $result[0] ) ) {
					return (string) $result[0];
				} elseif( is_string( $result ) ) {
					return $result;
				}
			}
		}

		return (string) $singular;
	}


	/**
	 * Returns the translated singular or plural form of the string depending on the given number.
	 *
	 * @param string $domain Translation domain
	 * @param string $singular String in singular form
	 * @param string $plural String in plural form
	 * @param int $number Quantity to choose the correct plural form for languages with plural forms
	 * @return string Returns the translated singular or plural form of the string depending on the given number
	 * @throws \Aimeos\Base\Translation\Exception Throws exception on initialization of the translation
	 */
	public function dn( string $domain, string $singular, string $plural, int $number ) : string
	{
		$idx = $this->getPluralIndex( (int) $number, $this->getLocale() );

		foreach( $this->getTranslations( $domain ) as $object )
		{
			if( ( $list = $object->get( $singular ) ) !== null && isset( $list[$idx] ) ) {
				return (string) $list[$idx];
			}
		}

		return ( $idx > 0 ? (string) $plural : $singular );
	}


	/**
	 * Returns all locale string of the given domain.
	 *
	 * @param string $domain Translation domain
	 * @return array Associative list with original string as key and associative list with index => translation as value
	 */
	public function all( string $domain ) : array
	{
		$messages = [];

		foreach( $this->getTranslations( $domain ) as $object ) {
			$messages = $messages + $object->all();
		}

		return $messages;
	}


	/**
	 * Returns the MO file objects which contain the translations.
	 *
	 * @param string $domain Translation domain
	 * @return array List of translation objects implementing \Aimeos\Base\Translation\File\Mo
	 * @throws \Aimeos\Base\Translation\Exception If initialization fails
	 */
	protected function getTranslations( string $domain ) : array
	{
		if( !isset( $this->files[$domain] ) )
		{
			if( !isset( $this->sources[$domain] ) )
			{
				$msg = sprintf( 'No translation directory for domain "%1$s" available', $domain );
				throw new \Aimeos\Base\Translation\Exception( $msg );
			}

			// Reverse locations so the former gets not overwritten by the later
			$locations = array_reverse( $this->getTranslationFileLocations( $this->sources[$domain], $this->getLocale() ) );

			foreach( $locations as $location ) {
				$this->files[$domain][$location] = new \Aimeos\Base\Translation\File\Mo( $location );
			}
		}

		return $this->files[$domain] ?? [];
	}

}
