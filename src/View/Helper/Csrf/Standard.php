<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 * @package Base
 * @subpackage View
 */


namespace Aimeos\Base\View\Helper\Csrf;


/**
 * View helper class for retrieving CSRF tokens.
 *
 * @package Base
 * @subpackage View
 */
class Standard
	extends \Aimeos\Base\View\Helper\Base
	implements \Aimeos\Base\View\Helper\Csrf\Iface
{
	private string $name;
	private ?string $value;
	private string $formfield = '';


	/**
	 * Initializes the URL view helper.
	 *
	 * @param \Aimeos\Base\View\Iface $view View instance with registered view helpers
	 * @param string $name CSRF token name
	 * @param string|null $value CSRF token value
	 */
	public function __construct( \Aimeos\Base\View\Iface $view, string $name = '', string $value = null )
	{
		parent::__construct( $view );

		$this->name = $name;
		$this->value = $value;

		if( $value ) {
			$this->formfield = '<input class="csrf-token" type="hidden" name="' . $this->name . '" value="' . $this->value . '">';
		}
	}


	/**
	 * Returns the CSRF partial object.
	 *
	 * @return \Aimeos\Base\View\Helper\Csrf\Iface CSRF partial object
	 */
	public function transform() : Iface
	{
		return $this;
	}


	/**
	 * Returns the CSRF token name.
	 *
	 * @return string CSRF token name
	 */
	public function name() : string
	{
		return $this->name;
	}


	/**
	 * Returns the CSRF token value.
	 *
	 * @return string|null CSRF token value
	 */
	public function value() : ?string
	{
		return $this->value;
	}


	/**
	 * Returns the HTML form field for the CSRF token.
	 *
	 * @return string HTML form field code
	 */
	public function formfield() : string
	{
		return $this->formfield;
	}
}
