<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2019-2022
 */


namespace Aimeos\Base\View\Helper\Link;


class StandardTest extends \PHPUnit\Framework\TestCase
{
	private $object;


	protected function setUp() : void
	{
		$config = ['test' => ['target' => 'module', 'controller' => 'test', 'action' => 'index']];
		$conf = new \Aimeos\Base\Config\PHPArray( $config );

		$view = new \Aimeos\Base\View\Standard();
		$view->addHelper( 'url', new \Aimeos\Base\View\Helper\Url\Standard( $view, '/baseurl/' ) );
		$view->addHelper( 'config', new \Aimeos\Base\View\Helper\Config\Standard( $view, $conf ) );
		$this->object = new \Aimeos\Base\View\Helper\Link\Standard( $view );
	}


	protected function tearDown() : void
	{
		unset( $this->object );
	}


	public function testTransform()
	{
		$expected = '/baseurl/module/test/index/?plain=1&multi%5Bsub%5D=1';
		$params = array( 'plain' => 1, 'multi' => array( 'sub' => true ) );

		$this->assertEquals( $expected, $this->object->transform( 'test', $params ) );
	}

}
