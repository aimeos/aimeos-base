<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 */


namespace Aimeos\Base\View\Helper\Formparam;


class StandardTest extends \PHPUnit\Framework\TestCase
{
	public function testTransform()
	{
		$view = new \Aimeos\Base\View\Standard();
		$object = new \Aimeos\Base\View\Helper\Formparam\Standard( $view );

		$this->assertEquals( 'test', $object->transform( 'test' ) );
		$this->assertEquals( 'test', $object->transform( array( 'test' ) ) );
	}


	public function testTransformMultiNames()
	{
		$view = new \Aimeos\Base\View\Standard();
		$object = new \Aimeos\Base\View\Helper\Formparam\Standard( $view );

		$this->assertEquals( 'test[test2]', $object->transform( array( 'test', 'test2' ) ) );
	}


	public function testTransformWithPrefix()
	{
		$view = new \Aimeos\Base\View\Standard();
		$object = new \Aimeos\Base\View\Helper\Formparam\Standard( $view, array( 'prefix' ) );

		$this->assertEquals( 'prefix[test]', $object->transform( 'test' ) );
		$this->assertEquals( 'prefix[test]', $object->transform( array( 'test' ) ) );
	}


	public function testTransformWithMultiPrefix()
	{
		$view = new \Aimeos\Base\View\Standard();
		$object = new \Aimeos\Base\View\Helper\Formparam\Standard( $view, array( 'pre', 'fix' ) );

		$this->assertEquals( 'pre[fix][test]', $object->transform( 'test' ) );
		$this->assertEquals( 'pre[fix][test]', $object->transform( array( 'test' ) ) );
	}


	public function testTransformWithNoPrefix()
	{
		$view = new \Aimeos\Base\View\Standard();
		$object = new \Aimeos\Base\View\Helper\Formparam\Standard( $view, array( 'prefix' ) );

		$this->assertEquals( 'test', $object->transform( 'test', false ) );
		$this->assertEquals( 'test', $object->transform( array( 'test' ), false ) );
	}

}
