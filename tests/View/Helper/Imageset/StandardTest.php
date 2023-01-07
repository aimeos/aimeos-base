<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2019-2023
 */


namespace Aimeos\Base\View\Helper\Imageset;


class StandardTest extends \PHPUnit\Framework\TestCase
{
	private $object;


	protected function setUp() : void
	{
		$conf = new \Aimeos\Base\Config\PHPArray( ['resource' => ['fs-test' => ['baseurl' => '/path/to']]] );
		$view = new \Aimeos\Base\View\Standard();

		$view->addHelper( 'config', new \Aimeos\Base\View\Helper\Config\Standard( $view, $conf ) );
		$view->addHelper( 'content', new \Aimeos\Base\View\Helper\Content\Standard( $view ) );

		$this->object = new \Aimeos\Base\View\Helper\Imageset\Standard( $view );
	}


	protected function tearDown() : void
	{
		unset( $this->object );
	}


	public function testTransform()
	{
		$expected = '/path/to/image-1.jpg 100w, /path/to/image-2.jpg 200w';
		$images = ['100' => 'image-1.jpg', '200' => 'image-2.jpg'];

		$this->assertEquals( $expected, $this->object->transform( $images, 'fs-test' ) );
	}
}
