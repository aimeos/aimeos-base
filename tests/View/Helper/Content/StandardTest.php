<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 */


namespace Aimeos\Base\View\Helper\Content;


class StandardTest extends \PHPUnit\Framework\TestCase
{
	private $object;


	protected function setUp() : void
	{
		$view = new \Aimeos\Base\View\Standard();

		$helper = new \Aimeos\Base\View\Helper\Encoder\Standard( $view );
		$view->addHelper( 'encoder', $helper );

		$helper = new \Aimeos\Base\View\Helper\Config\Standard( $view, \TestHelper::getConfig() );
		$view->addHelper( 'config', $helper );

		$this->object = new \Aimeos\Base\View\Helper\Content\Standard( $view );
	}


	protected function tearDown() : void
	{
		unset( $this->object );
	}


	public function testTransform()
	{
		$this->assertEquals( '', $this->object->transform( null ) );
	}


	public function testTransformRelativeUrl()
	{
		$view = new \Aimeos\Base\View\Standard();

		$helper = new \Aimeos\Base\View\Helper\Encoder\Standard( $view );
		$view->addHelper( 'encoder', $helper );

		$config = new \Aimeos\Base\Config\PHPArray( ['resource' => ['fs-test' => ['baseurl' => 'base/url']]] );
		$helper = new \Aimeos\Base\View\Helper\Config\Standard( $view, $config );
		$view->addHelper( 'config', $helper );

		$object = new \Aimeos\Base\View\Helper\Content\Standard( $view );

		$output = $object->transform( 'path/to/resource', 'fs-test' );
		$this->assertEquals( 'base/url/path/to/resource', $output );
	}


	public function testTransformVersion()
	{
		$view = new \Aimeos\Base\View\Standard();

		$helper = new \Aimeos\Base\View\Helper\Encoder\Standard( $view );
		$view->addHelper( 'encoder', $helper );

		$config = new \Aimeos\Base\Config\PHPArray( ['resource' => ['fs-test' => ['baseurl' => 'base/url']]] );
		$helper = new \Aimeos\Base\View\Helper\Config\Standard( $view, $config );
		$view->addHelper( 'config', $helper );

		$object = new \Aimeos\Base\View\Helper\Content\Standard( $view );

		$output = $object->transform( 'path/to/resource', 'fs-test', true );
		$this->assertEquals( 'base/url/path/to/resource?v=1', $output );
	}


	public function testTransformAbsoluteUrl()
	{
		$output = $this->object->transform( '/path/to/resource' );
		$this->assertEquals( '/path/to/resource', $output );
	}


	public function testTransformDataUrl()
	{
		$output = $this->object->transform( 'data:image/gif;base64,R0lGODdhAQABAIAAAAAAAAAAACwAAAAAAQABAAACAkQBADs=' );
		$this->assertEquals( 'data:image/gif;base64,R0lGODdhAQABAIAAAAAAAAAAACwAAAAAAQABAAACAkQBADs=', $output );
	}


	public function testTransformHttpUrl()
	{
		$output = $this->object->transform( 'https://host:443/path/to/resource' );
		$this->assertEquals( 'https://host:443/path/to/resource', $output );
	}
}
