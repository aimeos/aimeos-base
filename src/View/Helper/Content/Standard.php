<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 * @package Base
 * @subpackage View
 */


namespace Aimeos\Base\View\Helper\Content;


/**
 * View helper class for generating media URLs
 *
 * @package Base
 * @subpackage View
 */
class Standard
	extends \Aimeos\Base\View\Helper\Base
	implements \Aimeos\Base\View\Helper\Content\Iface
{
	private array $baseurls = [];
	private string $version;
	private \Aimeos\Base\View\Helper\Encoder\Iface $enc;


	/**
	 * Initializes the content view helper.
	 *
	 * @param \Aimeos\Base\View\Iface $view View instance with registered view helpers
	 */
	public function __construct( \Aimeos\Base\View\Iface $view )
	{
		parent::__construct( $view );

		$this->enc = $view->encoder();
		$this->version = $view->config( 'version', 1 );
	}


	/**
	 * Returns the complete encoded content URL.
	 *
	 * @param string|null $url Absolute, relative or data: URL
	 * @param string $fsname File system name the file is stored at
	 * @param bool $version TRUE to add file version, FALSE for not
	 * @return string Complete encoded content URL
	 */
	public function transform( ?string $url, $fsname = 'fs-media', bool $version = false ) : string
	{
		if( $url && !\Aimeos\Base\Str::starts( $url, ['http', 'data:', '/'] ) ) {
			$url = $this->baseurl( $fsname ) . '/' . $url . ( $version ? '?v=' . $this->version : '' );
		}

		return $this->enc->attr( $url );
	}


	/**
	 * Returns the base URL for the given file system.
	 *
	 * @param string $fsname File system name
	 * @return string Base URL of the file system
	 */
	protected function baseurl( string $fsname ) : string
	{
		if( !isset( $this->baseurls[$fsname] ) ) {
			$this->baseurls[$fsname] = rtrim( $this->view()->config( 'resource/' . $fsname . '/baseurl', '' ), '/' );
		}

		return $this->baseurls[$fsname];
	}
}
