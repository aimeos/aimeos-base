<?php

namespace Aimeos\Base\MQueue\Manager;


class StandardTest extends \PHPUnit\Framework\TestCase
{
	private $config;
	private $object;


	protected function setUp() : void
	{
		$this->config = \TestHelper::getConfig();
		$this->object = new \Aimeos\Base\MQueue\Manager\Standard( \TestHelper::getConfig()->get( 'resource', [] ) );
	}


	protected function tearDown() : void
	{
		unset( $this->object );
	}


	public function testClone()
	{
		$this->config->set( 'resource/mq-email', array( 'adapter' => 'None' ) );
		$this->assertInstanceof( 'Aimeos\Base\MQueue\Manager\Iface', clone $this->object );
	}


	public function testGet()
	{
		$this->config->set( 'resource/mq-email', array( 'adapter' => 'None' ) );
		$this->assertInstanceof( 'Aimeos\Base\MQueue\Iface', $this->object->get( 'mq-email' ) );
	}


	public function testGetFallback()
	{
		$this->config->set( 'resource/mq', array( 'adapter' => 'None' ) );
		$this->assertInstanceof( 'Aimeos\Base\MQueue\Iface', $this->object->get( 'mq-email' ) );
	}


	public function testGetException()
	{
		$object = new \Aimeos\Base\MQueue\Manager\Standard( [] );

		$this->expectException( \Aimeos\Base\MQueue\Exception::class );
		$object->get( 'xx' );
	}


	public function testGetDatabaseConfig()
	{
		$this->config->set( 'resource/mq-email', array( 'adapter' => 'Standard', 'db' => 'db' ) );
		$this->assertInstanceof( 'Aimeos\Base\MQueue\Iface', $this->object->get( 'mq-email' ) );
	}
}
