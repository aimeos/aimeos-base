<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2022-2023
 */


namespace Aimeos\Base\Translation\File;


class MoTest extends \PHPUnit\Framework\TestCase
{
	public function testConstructorException()
	{
		$this->expectException( \Aimeos\Base\Translation\Exception::class );
		new Mo( 'notexisting' );
	}


	public function testAll()
	{
		$object = new Mo( dirname( __DIR__ ) . '/testfiles/case1/de' );
		$this->assertArrayHasKey( 'Car', $object->all() );
		$this->assertArrayHasKey( 'File', $object->all() );
		$this->assertArrayHasKey( 'Update', $object->all() );
	}


	public function testBigEndianFile()
	{
		$object = new Mo( dirname( __DIR__ ) . '/testfiles/bigendian' );
		$this->assertEquals( [], $object->all() );
	}


	public function testEmptyFile()
	{
		$this->expectException( \Aimeos\Base\Translation\Exception::class );
		new Mo( dirname( __DIR__ ) . '/testfiles/empty' );
	}


	public function testGet()
	{
		$object = new Mo( dirname( __DIR__ ) . '/testfiles/case1/de' );
		$this->assertEquals( 'Aktualisierung', $object->get( 'Update' ) );
	}


	public function testGetFallback()
	{
		$object = new Mo( dirname( __DIR__ ) . '/testfiles/case1/de' );
		$this->assertEquals( null, $object->get( 'unknown' ) );
	}


	public function testInvalidFile()
	{
		$this->expectException( \Aimeos\Base\Translation\Exception::class );
		new Mo( dirname( __DIR__ ) . '/testfiles/invalid' );
	}
}
