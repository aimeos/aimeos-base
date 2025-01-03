<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2025
 */


namespace Aimeos\Base\View\Helper\Partial;


class StandardTest extends \PHPUnit\Framework\TestCase
{
	private $object;


	protected function setUp() : void
	{
		$view = new \Aimeos\Base\View\Standard( array( __DIR__ => array( 'testfiles' ) ) );

		$this->object = new \Aimeos\Base\View\Helper\Partial\Standard( $view );
	}


	protected function tearDown() : void
	{
		$this->object = null;
	}


	public function testTransform()
	{
		$this->assertEquals( '', $this->object->transform( 'partial' ) );
	}


	public function testTransformParams()
	{
		$this->assertEquals( 'test', $this->object->transform( 'partial', array( 'testparam' => 'test' ) ) );
	}
}
