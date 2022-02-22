<?php

namespace Aimeos\Base\MQueue\Manager;


class StandardTest extends \PHPUnit\Framework\TestCase
{
	private $config;
	private $object;


	protected function setUp() : void
	{
		$this->config = \TestHelper::getConfig();
		$this->object = new \Aimeos\Base\MQueue\Manager\Standard( $this->config );
	}


	protected function tearDown() : void
	{
		$this->config->set( 'resource/mq-email', null );
		$this->config->set( 'resource/mq', null );

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
		$this->expectException( \Aimeos\Base\MQueue\Exception::class );
		$this->object->get( 'xx' );
	}


	public function testGetDatabaseConfig()
	{
		$this->config->set( 'resource/mq-email', array( 'adapter' => 'None', 'db' => 'db' ) );
		$this->assertInstanceof( 'Aimeos\Base\MQueue\Iface', $this->object->get( 'mq-email' ) );
	}
}
