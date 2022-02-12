<?php

namespace Aimeos\Base\Logger;


class DBTest extends \PHPUnit\Framework\TestCase
{
	private static $dbm;
	private $object;


	public static function setUpBeforeClass() : void
	{
		self::$dbm = \TestHelper::getDBManager();

		if( !( self::$dbm instanceof \Aimeos\Base\DB\Manager\DBAL ) ) {
			return;
		}

		$schema = new \Doctrine\DBAL\Schema\Schema();

		$table = $schema->createTable( 'mw_log_test' );
		$table->addColumn( 'facility', 'string', array( 'length' => 32 ) );
		$table->addColumn( 'request', 'string', array( 'length' => 32 ) );
		$table->addColumn( 'tstamp', 'string', array( 'length' => 20 ) );
		$table->addColumn( 'priority', 'integer', [] );
		$table->addColumn( 'message', 'text', array( 'length' => 0xffff ) );

		$conn = self::$dbm->acquire();

		foreach( $schema->toSQL( $conn->getRawObject()->getDatabasePlatform() ) as $sql ) {
			$conn->create( $sql )->execute()->finish();
		}

		self::$dbm->release( $conn );
	}


	public static function tearDownAfterClass() : void
	{
		if( self::$dbm instanceof \Aimeos\Base\DB\Manager\DBAL )
		{
			$conn = self::$dbm->acquire();

			$conn->create( 'DROP TABLE "mw_log_test"' )->execute()->finish();

			self::$dbm->release( $conn );
		}
	}


	protected function setUp() : void
	{
		if( !( self::$dbm instanceof \Aimeos\Base\DB\Manager\DBAL ) ) {
			$this->markTestSkipped( 'No DBAL database manager configured' );
		}


		$conn = self::$dbm->acquire();

		$sql = 'INSERT INTO "mw_log_test" ( "facility", "tstamp", "priority", "message", "request" ) VALUES ( ?, ?, ?, ?, ? )';
		$this->object = new \Aimeos\Base\Logger\DB( $conn->create( $sql ) );

		self::$dbm->release( $conn );
	}


	protected function tearDown() : void
	{
		if( self::$dbm instanceof \Aimeos\Base\DB\Manager\DBAL )
		{
			$conn = self::$dbm->acquire();

			$conn->create( 'DELETE FROM "mw_log_test"' )->execute()->finish();

			self::$dbm->release( $conn );
		}
	}


	public function testLog()
	{
		$this->object->log( 'error' );

		$conn = self::$dbm->acquire();

		$result = $conn->create( 'SELECT * FROM "mw_log_test"' )->execute();
		$row = $result->fetch();

		self::$dbm->release( $conn );

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
		$conn = self::$dbm->acquire();
		$conn->create( 'DELETE FROM "mw_log_test"' )->execute()->finish();

		$this->object->log( array( 'scalar', 'errortest' ) );

		$result = $conn->create( 'SELECT * FROM "mw_log_test"' )->execute();

		$row = $result->fetch();

		self::$dbm->release( $conn );

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

		$conn = self::$dbm->acquire();

		$result = $conn->create( 'SELECT * FROM "mw_log_test"' )->execute();
		$row = $result->fetch();

		self::$dbm->release( $conn );

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

		$conn = self::$dbm->acquire();

		$result = $conn->create( 'SELECT * FROM "mw_log_test"' )->execute();
		$row = $result->fetch();

		self::$dbm->release( $conn );

		$this->assertNull( $row );
	}


	public function testFacility()
	{
		$this->object->log( 'user auth', \Aimeos\Base\Logger\Iface::ERR, 'auth' );

		$conn = self::$dbm->acquire();

		$result = $conn->create( 'SELECT * FROM "mw_log_test"' )->execute();
		$row = $result->fetch();

		self::$dbm->release( $conn );

		if( $row === null ) {
			throw new \RuntimeException( 'No log record found' );
		}

		$this->assertEquals( 'auth', $row['facility'] );
	}
}
