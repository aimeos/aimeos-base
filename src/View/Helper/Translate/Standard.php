<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2025
 * @package Base
 * @subpackage View
 */


namespace Aimeos\Base\View\Helper\Translate;


/**
 * View helper class for translating strings.
 *
 * @package Base
 * @subpackage View
 */
class Standard
	extends \Aimeos\Base\View\Helper\Base
	implements \Aimeos\Base\View\Helper\Translate\Iface
{
	private \Aimeos\Base\Translation\Iface $translator;


	/**
	 * Initializes the translator view helper.
	 *
	 * @param \Aimeos\Base\View\Iface $view View instance with registered view helpers
	 * @param \Aimeos\Base\Translation\Iface $translator Translation object
	 */
	public function __construct( \Aimeos\Base\View\Iface $view, \Aimeos\Base\Translation\Iface $translator )
	{
		parent::__construct( $view );

		$this->translator = $translator;
	}


	/**
	 * Returns the translated string or the original one if no translation is available.
	 *
	 * @param string $domain Translation domain from core or an extension
	 * @param string|null $singular Singular form of the text to translate
	 * @param string|null $plural Plural form of the text, used if $number is greater than one
	 * @param int $number Amount of things relevant for the plural form
	 * @param bool $force Return string untranslated if no translation is available
	 * @return string|null Translated string or NULL if no translation is available and force parameter is FALSE
	 */
	public function transform( string $domain, ?string $singular, ?string $plural = null, int $number = 1, bool $force = true ) : ?string
	{
		if( $plural )
		{
			$trans = $this->translator->dn( $domain, (string) $singular, $plural, $number );
			return $trans !== $plural || $force ? $trans : null;
		}

		$trans = $this->translator->dt( $domain, (string) $singular );
		return $trans !== $singular || $force ? $trans : null;
	}
}
