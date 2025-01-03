<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2025
 */

namespace Aimeos\Base\DB\Connection;


class NoneTest extends \PHPUnit\Framework\TestCase
{
	private $object;


	protected function setUp() : void
	{
		$this->object = new \Aimeos\Base\DB\Connection\None();
	}


	protected function tearDown() : void
	{
		unset( $this->object );
	}


	public function testClose()
	{
		$this->expectException( \Aimeos\Base\DB\Exception::class );
		$this->object->close();
	}


	public function testConnect()
	{
		$this->expectException( \Aimeos\Base\DB\Exception::class );
		$this->object->connect();
	}


	public function testCreate()
	{
		$this->expectException( \Aimeos\Base\DB\Exception::class );
		$this->object->create( 'SELECT' );
	}


	public function testGetRawObject()
	{
		$this->expectException( \Aimeos\Base\DB\Exception::class );
		$this->object->getRawObject();
	}


	public function testBegin()
	{
		$this->expectException( \Aimeos\Base\DB\Exception::class );
		$this->object->begin();
	}


	public function testCommit()
	{
		$this->expectException( \Aimeos\Base\DB\Exception::class );
		$this->object->commit();
	}


	public function testRollback()
	{
		$this->expectException( \Aimeos\Base\DB\Exception::class );
		$this->object->rollback();
	}
}
