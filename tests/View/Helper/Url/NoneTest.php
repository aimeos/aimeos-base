<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 */


namespace Aimeos\Base\View\Helper\Url;


class NoneTest extends \PHPUnit\Framework\TestCase
{
	private $object;


	protected function setUp() : void
	{
		$view = new \Aimeos\Base\View\Standard();
		$this->object = new \Aimeos\Base\View\Helper\Url\None( $view );
	}


	protected function tearDown() : void
	{
		$this->object = null;
	}


	public function testTransform()
	{
		$params = array( 'plain' => 1, 'multi' => array( 'sub' => true ) );
		$trailing = array( 'some', 'nice', 'text' );

		$this->assertEquals( '', $this->object->transform( 'module', 'test', 'index', $params, $trailing ) );
	}

}
