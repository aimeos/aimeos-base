<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 * @package Base
 * @subpackage View
 */


namespace Aimeos\Base\View\Helper\Date;


/**
 * View helper class for formatting dates.
 *
 * @package Base
 * @subpackage View
 */
class Standard
	extends \Aimeos\Base\View\Helper\Base
	implements \Aimeos\Base\View\Helper\Date\Iface
{
	private string $format;


	/**
	 * Initializes the Date view helper.
	 *
	 * @param \Aimeos\Base\View\Iface $view View instance with registered view helpers
	 * @param string $format New date format
	 * @see https://www.php.net/manual/en/datetime.createfromformat.php
	 */
	public function __construct( \Aimeos\Base\View\Iface $view, ?string $format = null )
	{
		parent::__construct( $view );

		$this->format = $format ?: 'Y-m-d';
	}


	/**
	 * Returns the formatted date.
	 *
	 * @param string $date ISO date and time
	 * @return string Formatted date
	 */
	public function transform( string $date ) : string
	{
		return \DateTime::createFromFormat( 'Y-m-d H:i:s', $date )->format( $this->format );
	}
}
