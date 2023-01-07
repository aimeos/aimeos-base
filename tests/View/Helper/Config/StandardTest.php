<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 */


namespace Aimeos\Base\View\Helper\Config;


class StandardTest extends \PHPUnit\Framework\TestCase
{
	private $object;


	protected function setUp() : void
	{
		$view = new \Aimeos\Base\View\Standard();

		$config = array(
			'page' => 'test',
			'sub' => array(
				'subpage' => 'test2',
			),
		);

		$conf = new \Aimeos\Base\Config\PHPArray( $config );
		$this->object = new \Aimeos\Base\View\Helper\Config\Standard( $view, $conf );
	}


	protected function tearDown() : void
	{
		$this->object = null;
	}


	public function testTransform()
	{
		$this->assertEquals( 'test', $this->object->transform( 'page', 'none' ) );
		$this->assertEquals( 'none', $this->object->transform( 'missing', 'none' ) );
	}


	public function testTransformPath()
	{
		$this->assertEquals( 'test2', $this->object->transform( 'sub/subpage', 'none' ) );
		$this->assertEquals( array( 'subpage' => 'test2' ), $this->object->transform( 'sub' ) );
	}


	public function testTransformNoDefault()
	{
		$this->assertEquals( 'test', $this->object->transform( 'page' ) );
		$this->assertEquals( null, $this->object->transform( 'missing' ) );
	}

}
