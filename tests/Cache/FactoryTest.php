<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 */


namespace Aimeos\Base\Cache;


class FactoryTest extends \PHPUnit\Framework\TestCase
{
	public function testFactory()
	{
		$config = array(
			'sql' => array(
				'delete' => '', 'deletebytag' => '',
				'get' => '', 'getbytag' => '',
				'set' => '', 'settag' => ''
			),
			'search' => array(
				'cache.id' => '', 'cache.siteid' => '', 'cache.value' => '',
				'cache.expire' => '', 'cache.tag.name' => ''
			),
		);

		$object = \Aimeos\Base\Cache\Factory::create( 'None', $config, \TestHelper::getConnection() );
		$this->assertInstanceOf( \Aimeos\Base\Cache\Iface::class, $object );
	}


	public function testFactoryUnknown()
	{
		$this->expectException( \Aimeos\Base\Cache\Exception::class );
		\Aimeos\Base\Cache\Factory::create( 'unknown' );
	}


	public function testFactoryInvalidCharacters()
	{
		$this->expectException( \Aimeos\Base\Cache\Exception::class );
		\Aimeos\Base\Cache\Factory::create( '$$$' );
	}


	public function testFactoryInvalidClass()
	{
		$this->expectException( \Aimeos\Base\Cache\Exception::class );
		\Aimeos\Base\Cache\Factory::create( 'InvalidCache' );
	}
}


class InvalidCache
{
}
