<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2023
 */


namespace Aimeos\Base\View\Helper\Session;


class StandardTest extends \PHPUnit\Framework\TestCase
{
	private $object;
	private $session;


	protected function setUp() : void
	{
		$view = new \Aimeos\Base\View\Standard();
		$this->session = new \Aimeos\Base\Session\None();

		$this->object = new \Aimeos\Base\View\Helper\Session\Standard( $view, $this->session );
	}


	protected function tearDown() : void
	{
		unset( $this->object, $this->session );
	}


	public function testTransform()
	{
		$this->session->set( 'page', 'test' );

		$this->assertEquals( 'test', $this->object->transform( 'page', 'none' ) );
		$this->assertEquals( 'none', $this->object->transform( 'missing', 'none' ) );
	}


	public function testTransformNoDefault()
	{
		$this->session->set( 'page', 'test' );

		$this->assertEquals( 'test', $this->object->transform( 'page' ) );
		$this->assertEquals( null, $this->object->transform( 'missing' ) );
	}
}
