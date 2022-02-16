<?php

namespace Aimeos\Base\Filesystem\Manager;


class StandardTest extends \PHPUnit\Framework\TestCase
{
	public function testGet()
	{
		$config = \TestHelper::getConfig()->get( 'resource' );
		$config['fs-media'] = [
			'adapter' => 'Standard',
			'basedir' => __DIR__
		];

		$object = new \Aimeos\Base\Filesystem\Manager\Standard( $config );

		$this->assertInstanceof( 'Aimeos\Base\Filesystem\Iface', $object->get( 'fs-media' ) );
	}


	public function testGetFallback()
	{
		$config = \TestHelper::getConfig()->get( 'resource' );
		$config['fs'] = [
			'adapter' => 'Standard',
			'basedir' => __DIR__
		];

		$object = new \Aimeos\Base\Filesystem\Manager\Standard( $config );

		$this->assertInstanceof( 'Aimeos\Base\Filesystem\Iface', $object->get( 'fs-media' ) );
	}
}
