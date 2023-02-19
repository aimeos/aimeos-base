<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 * @package Base
 * @subpackage View
 */


namespace Aimeos\Base\View\Helper\Number;


/**
 * View helper class for formatting numbers.
 *
 * @package Base
 * @subpackage View
 */
class Locale
	extends \Aimeos\Base\View\Helper\Base
	implements \Aimeos\Base\View\Helper\Number\Iface
{
	private \NumberFormatter $formatter;


	/**
	 * Initializes the Number view helper.
	 *
	 * @param \Aimeos\Base\View\Iface $view View instance with registered view helpers
	 * @param string|null $locale Language locale like "en" or "en_UK" or null for default
	 * @param string|null $pattern ICU pattern to format the number or null for default formatting
	 */
	public function __construct( \Aimeos\Base\View\Iface $view, string $locale = null, string $pattern = null )
	{
		parent::__construct( $view );

		if( $pattern !== null ) {
			$this->formatter = new \NumberFormatter( $locale ?: 'en', \NumberFormatter::PATTERN_DECIMAL, $pattern );
		} else {
			$this->formatter = new \NumberFormatter( $locale ?: 'en', \NumberFormatter::DECIMAL );
		}
	}


	/**
	 * Returns the formatted number.
	 *
	 * @param int|double|string $number Number to format
	 * @param int|null $decimals Number of decimals behind the decimal point or null for default value
	 * @return string Formatted number
	 */
	public function transform( $number, int $decimals = null ) : string
	{
		$this->formatter->setAttribute( \NumberFormatter::FRACTION_DIGITS, $decimals !== null ? (int) $decimals : 2 );
		return $this->formatter->format( (double) $number );
	}
}
