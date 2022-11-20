<?php

namespace Aimeos\Base\Logger;


class DBTest extends \PHPUnit\Framework\TestCase
{
	private static $conn;
	private $object;


	public static function setUpBeforeClass() : void
	{
		$schema = new \Doctrine\DBAL\Schema\Schema();

		$table = $schema->createTable( 'mw_log_test' );
		$table->addColumn( 'facility', 'string', array( 'length' => 32 ) );
		$table->addColumn( 'request', 'string', array( 'length' => 32 ) );
		$table->addColumn( 'tstamp', 'string', array( 'length' => 20 ) );
		$table->addColumn( 'priority', 'integer', [] );
		$table->addColumn( 'message', 'text', array( 'length' => 0xffff ) );

		self::$conn = \TestHelper::getConnection();

		foreach( $schema->toSQL( self::$conn->getRawObject()->getDatabasePlatform() ) as $sql ) {
			self::$conn->create( $sql )->execute()->finish();
		}
	}


	public static function tearDownAfterClass() : void
	{
		self::$conn->create( 'DROP TABLE "mw_log_test"' )->execute()->finish();
	}


	protected function setUp() : void
	{
		$sql = 'INSERT INTO "mw_log_test" ( "facility", "tstamp", "priority", "message", "request" ) VALUES ( ?, ?, ?, ?, ? )';
		$this->object = new \Aimeos\Base\Logger\DB( self::$conn->create( $sql ) );
	}


	protected function tearDown() : void
	{
		self::$conn->create( 'DELETE FROM "mw_log_test"' )->execute()->finish();
	}


	public function testLog()
	{
		$this->object->log( 'error' );

		$result = self::$conn->create( 'SELECT * FROM "mw_log_test"' )->execute();
		$row = $result->fetch();

		if( $row === null ) {
			throw new \RuntimeException( 'No log record found' );
		}

		$this->assertEquals( 'message', $row['facility'] );
		$this->assertEquals( 32, strlen( $row['request'] ) );
		$this->assertEquals( 1, preg_match( '/[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}/', $row['tstamp'] ) );
		$this->assertEquals( \Aimeos\Base\Logger\Iface::ERR, $row['priority'] );
		$this->assertEquals( 'error', $row['message'] );


		$this->expectException( \Aimeos\Base\Logger\Exception::class );
		$this->object->log( 'wrong log level', -1 );
	}


	public function testScalarLog()
	{
		self::$conn->create( 'DELETE FROM "mw_log_test"' )->execute()->finish();

		$this->object->log( array( 'scalar', 'errortest' ) );

		$result = self::$conn->create( 'SELECT * FROM "mw_log_test"' )->execute();

		$row = $result->fetch();

		if( $row === null ) {
			throw new \RuntimeException( 'No log record found' );
		}

		$this->assertEquals( 'message', $row['facility'] );
		$this->assertEquals( 32, strlen( $row['request'] ) );
		$this->assertEquals( 1, preg_match( '/[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}/', $row['tstamp'] ) );
		$this->assertEquals( \Aimeos\Base\Logger\Iface::ERR, $row['priority'] );
		$this->assertEquals( '["scalar","errortest"]', $row['message'] );
	}


	public function testLogCrit()
	{
		$this->object->log( 'critical', \Aimeos\Base\Logger\Iface::CRIT );

		$result = self::$conn->create( 'SELECT * FROM "mw_log_test"' )->execute();
		$row = $result->fetch();

		if( $row === null ) {
			throw new \RuntimeException( 'No log record found' );
		}

		$this->assertEquals( 32, strlen( $row['request'] ) );
		$this->assertEquals( 1, preg_match( '/[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}/', $row['tstamp'] ) );
		$this->assertEquals( \Aimeos\Base\Logger\Iface::CRIT, $row['priority'] );
		$this->assertEquals( 'critical', $row['message'] );
	}


	public function testLogWarn()
	{
		$this->object->log( 'debug', \Aimeos\Base\Logger\Iface::WARN );

		$result = self::$conn->create( 'SELECT * FROM "mw_log_test"' )->execute();
		$row = $result->fetch();

		$this->assertNull( $row );
	}


	public function testFacility()
	{
		$this->object->log( 'user auth', \Aimeos\Base\Logger\Iface::ERR, 'auth' );

		$result = self::$conn->create( 'SELECT * FROM "mw_log_test"' )->execute();
		$row = $result->fetch();

		if( $row === null ) {
			throw new \RuntimeException( 'No log record found' );
		}

		$this->assertEquals( 'auth', $row['facility'] );
	}
}
