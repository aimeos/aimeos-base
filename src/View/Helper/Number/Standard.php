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
class Standard
	extends \Aimeos\Base\View\Helper\Base
	implements \Aimeos\Base\View\Helper\Number\Iface
{
	private string $dsep;
	private string $tsep;
	private int $decimals;


	/**
	 * Initializes the Number view helper.
	 *
	 * @param \Aimeos\Base\View\Iface $view View instance with registered view helpers
	 * @param string $decimalSeparator Character for the decimal point
	 * @param string $thousandsSeperator Character separating groups of thousands
	 * @param int $decimals Number of decimal digits
	 */
	public function __construct( \Aimeos\Base\View\Iface $view, string $decimalSeparator = '.', string $thousandsSeperator = '', int $decimals = 2 )
	{
		parent::__construct( $view );

		$this->dsep = $decimalSeparator;
		$this->tsep = $thousandsSeperator;
		$this->decimals = $decimals;
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
		if( $decimals === null ) {
			$decimals = $this->decimals;
		}

		return number_format( (double) $number, $decimals, $this->dsep, $this->tsep );
	}
}
