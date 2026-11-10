<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2026
 */


namespace Aimeos\Base\View\Helper\Idn;


class StandardTest extends \PHPUnit\Framework\TestCase
{
	private $object;


	protected function setUp() : void
	{
		$view = new \Aimeos\Base\View\Standard();
		$this->object = new \Aimeos\Base\View\Helper\Idn\Standard( $view );
	}


	protected function tearDown() : void
	{
		unset( $this->object );
	}


	public function testTransform()
	{
		$this->assertEquals( 'info@müller.de', $this->object->transform( 'info@xn--mller-kva.de' ) );
		$this->assertEquals( 'info@straße.de', $this->object->transform( 'info@xn--strae-oqa.de' ) );
		$this->assertEquals( 'info@shop.müller.de', $this->object->transform( 'info@shop.xn--mller-kva.de' ) );
	}


	public function testTransformAscii()
	{
		$this->assertEquals( 'xn--test@example.com', $this->object->transform( 'xn--test@example.com' ) );
		$this->assertEquals( 'info@example.com', $this->object->transform( 'info@example.com' ) );
	}


	public function testTransformEmpty()
	{
		$this->assertEquals( '', $this->object->transform( null ) );
		$this->assertEquals( '', $this->object->transform( '' ) );
		$this->assertEquals( 'test', $this->object->transform( 'test' ) );
	}


	public function testTransformInvalid()
	{
		$this->assertEquals( 'info@xn--.de', $this->object->transform( 'info@xn--.de' ) );
	}


	public function testTransformView()
	{
		$view = new \Aimeos\Base\View\Standard();
		$this->assertEquals( 'info@müller.de', $view->idn( 'info@xn--mller-kva.de' ) );
	}
}
