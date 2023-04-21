<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 */


namespace Aimeos\Base\View\Helper\Url;


class StandardTest extends \PHPUnit\Framework\TestCase
{
	private $object;


	protected function setUp() : void
	{
		$view = new \Aimeos\Base\View\Standard();
		$this->object = new \Aimeos\Base\View\Helper\Url\Standard( $view, '/baseurl/' );
	}


	protected function tearDown() : void
	{
		$this->object = null;
	}


	public function testTransform()
	{
		$expected = '/baseurl/module/test/index/some-nice-text?plain=1&multi%5Bsub%5D=1';
		$params = array( 'plain' => 1, 'multi' => array( 'sub' => true ) );
		$trailing = array( 'some', 'nice', 'text' );

		$this->assertEquals( $expected, $this->object->transform( 'module', 'test', 'index', $params, $trailing ) );
	}


	public function testTransformNoTrailing()
	{
		$expected = '/baseurl/module/test/index/?plain=1&multi%5Bsub%5D=1';
		$params = array( 'plain' => 1, 'multi' => array( 'sub' => true ) );

		$this->assertEquals( $expected, $this->object->transform( 'module', 'test', 'index', $params ) );
	}


	public function testTransformOnlyBase()
	{
		$this->assertEquals( '/baseurl/', $this->object->transform() );
	}


	public function testTransformSanitize()
	{
		$expected = '/baseurl/module/test/index/?f_name=tech-1m-2&d_name=weird-a-b-123';
		$params = array( 'f_name' => 'tech /1m & 2%', 'd_name' => 'weird #`[a]-{b}\\|123^?' );

		$this->assertEquals( $expected, $this->object->transform( 'module', 'test', 'index', $params ) );
	}

}
