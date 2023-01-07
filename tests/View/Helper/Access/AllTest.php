<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2023
 */


namespace Aimeos\Base\View\Helper\Access;


class AllTest extends \PHPUnit\Framework\TestCase
{
	public function testTransform()
	{
		$view = new \Aimeos\Base\View\Standard();
		$object = new \Aimeos\Base\View\Helper\Access\All( $view );
		$this->assertTrue( $object->transform( 'editor' ) );
	}
}
