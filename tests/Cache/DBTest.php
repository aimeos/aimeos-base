<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2014
 * @copyright Aimeos (aimeos.org), 2015-2022
 */


namespace Aimeos\Base\Cache;


class DBTest extends \PHPUnit\Framework\TestCase
{
	private static $conn;
	private static $dbm;
	private $config;
	private $object;


	public static function setUpBeforeClass() : void
	{
		self::$dbm = \TestHelper::getDBManager();

		if( !( self::$dbm instanceof \Aimeos\Base\DB\Manager\DBAL ) ) {
			return;
		}

		self::$conn = self::$dbm->acquire();

		$schema = new \Doctrine\DBAL\Schema\Schema();

		$cacheTable = $schema->createTable( 'mw_cache_test' );
		$cacheTable->addColumn( 'id', 'string', array( 'length' => 255 ) );
		$cacheTable->addColumn( 'expire', 'datetime', array( 'notnull' => false ) );
		$cacheTable->addColumn( 'value', 'text', array( 'length' => 0xffff ) );
		$cacheTable->setPrimaryKey( array( 'id' ) );
		$cacheTable->addIndex( array( 'expire' ) );

		foreach( $schema->toSQL( self::$conn->getRawObject()->getDatabasePlatform() ) as $sql ) {
			self::$conn->create( $sql )->execute()->finish();
		}

		$schema = new \Doctrine\DBAL\Schema\Schema();

		$tagTable = $schema->createTable( 'mw_cache_tag_test' );
		$tagTable->addColumn( 'tid', 'string', array( 'length' => 255 ) );
		$tagTable->addColumn( 'tname', 'string', array( 'length' => 255 ) );
		$tagTable->addUniqueIndex( array( 'tid', 'tname' ) );
		$tagTable->addForeignKeyConstraint( 'mw_cache_test', array( 'tid' ), array( 'id' ), array( 'onDelete' => 'CASCADE' ) );

		foreach( $schema->toSQL( self::$conn->getRawObject()->getDatabasePlatform() ) as $sql ) {
			self::$conn->create( $sql )->execute()->finish();
		}
	}


	public static function tearDownAfterClass() : void
	{
		if( self::$dbm instanceof \Aimeos\Base\DB\Manager\DBAL )
		{
			$conn = self::$dbm->acquire();

			$conn->create( 'DROP TABLE "mw_cache_tag_test"' )->execute()->finish();
			$conn->create( 'DROP TABLE "mw_cache_test"' )->execute()->finish();

			self::$dbm->release( self::$conn );
		}
	}


	protected function setUp() : void
	{
		if( !( self::$dbm instanceof \Aimeos\Base\DB\Manager\DBAL ) ) {
			$this->markTestSkipped( 'No DBAL database manager configured' );
		}


		$sql = 'INSERT INTO "mw_cache_test" ("id", "expire", "value") VALUES (\'t:1\', NULL, \'test 1\')';
		self::$conn->create( $sql )->execute()->finish();

		$sql = 'INSERT INTO "mw_cache_test" ("id", "expire", "value") VALUES (\'t:2\', \'2000-01-01 00:00:00\', \'test 2\')';
		self::$conn->create( $sql )->execute()->finish();

		$sql = 'INSERT INTO "mw_cache_tag_test" ("tid", "tname") VALUES (\'t:1\', \'tag:1\')';
		self::$conn->create( $sql )->execute()->finish();


		$this->config = [
			'cleanup' => '
				DELETE FROM "mw_cache_test" WHERE "expire" < ?
			',
			'clear' => '
				DELETE FROM "mw_cache_test"
			',
			'delete' => '
				DELETE FROM "mw_cache_test" WHERE "id" IN (?)
			',
			'deletebytag' => '
				DELETE FROM "mw_cache_test" WHERE "id" IN (
					SELECT "tid" FROM "mw_cache_tag_test" WHERE "tname" IN (?)
				)
			',
			'get' => '
				SELECT "id", "value", "expire" FROM "mw_cache_test"
				WHERE ( "expire" >= ? OR "expire" IS NULL ) AND "id" IN (?)
			',
			'set' => '
				INSERT INTO "mw_cache_test" ( "id", "expire", "value" ) VALUES ( ?, ?, ? )
			',
			'settag' => '
				INSERT INTO "mw_cache_tag_test" ( "tid", "tname" ) VALUES ( ?, ? )
			',
		];

		$this->object = new \Aimeos\Base\Cache\DB( $this->config, self::$conn );
	}


	public function tearDown() : void
	{
		if( self::$dbm instanceof \Aimeos\Base\DB\Manager\DBAL )
		{
			self::$conn->create( 'DELETE FROM "mw_cache_tag_test"' )->execute()->finish();
			self::$conn->create( 'DELETE FROM "mw_cache_test"' )->execute()->finish();
		}
	}


	public function testCleanup()
	{
		$this->assertTrue( $this->object->cleanup() );

		$conn = self::$dbm->acquire();
		$result = $conn->create( 'SELECT "id" FROM "mw_cache_test"' )->execute();
		self::$dbm->release( $conn );

		$this->assertEquals( array( 'id' => 't:1' ), $result->fetch() );
		$this->assertNull( $result->fetch() );
	}


	public function testDelete()
	{
		$this->assertTrue( $this->object->delete( 't:1' ) );

		$conn = self::$dbm->acquire();
		$row = $conn->create( 'SELECT * FROM "mw_cache_tag_test"' )->execute()->fetch();
		self::$dbm->release( $conn );

		$this->assertNull( $row );


		$conn = self::$dbm->acquire();
		$result = $conn->create( 'SELECT "id" FROM "mw_cache_test"' )->execute();
		self::$dbm->release( $conn );

		$this->assertEquals( array( 'id' => 't:2' ), $result->fetch() );
		$this->assertNull( $result->fetch() );
	}


	public function testDeleteMultiple()
	{
		$this->assertTrue( $this->object->deleteMultiple( array( 't:1', 't:2' ) ) );

		$conn = self::$dbm->acquire();
		$row = $conn->create( 'SELECT * FROM "mw_cache_test"' )->execute()->fetch();
		self::$dbm->release( $conn );

		$this->assertNull( $row );
	}


	public function testDeleteByTags()
	{
		$this->assertTrue( $this->object->deleteByTags( array( 'tag:1' ) ) );

		$conn = self::$dbm->acquire();
		$row = $conn->create( 'SELECT * FROM "mw_cache_tag_test"' )->execute()->fetch();
		self::$dbm->release( $conn );

		$this->assertNull( $row );


		$conn = self::$dbm->acquire();
		$result = $conn->create( 'SELECT "id" FROM "mw_cache_test"' )->execute();
		self::$dbm->release( $conn );

		$this->assertEquals( array( 'id' => 't:2' ), $result->fetch() );
		$this->assertNull( $result->fetch() );
	}


	public function testClear()
	{
		$this->assertTrue( $this->object->clear() );

		$conn = self::$dbm->acquire();
		$row = $conn->create( 'SELECT * FROM "mw_cache_tag_test"' )->execute()->fetch();
		self::$dbm->release( $conn );

		$this->assertNull( $row );


		$conn = self::$dbm->acquire();
		$row = $conn->create( 'SELECT "id" FROM "mw_cache_test"' )->execute()->fetch();
		self::$dbm->release( $conn );

		$this->assertNull( $row );
	}


	public function testGet()
	{
		$this->assertEquals( 'test 1', $this->object->get( 't:1' ) );
	}


	public function testGetExpired()
	{
		$this->assertEquals( null, $this->object->get( 't:2' ) );
	}


	public function testGetMultiple()
	{
		$this->assertEquals( array( 't:1' => 'test 1', 't:2' => null ), $this->object->getMultiple( array( 't:1', 't:2' ) ) );
	}


	public function testHas()
	{
		$this->assertTrue( $this->object->has( 't:1' ) );
	}


	public function testSet()
	{
		$this->assertTrue( $this->object->set( 't:3', 'test 3', '2100-01-01 00:00:00', ['tag:2', 'tag:3'] ) );


		$conn = self::$dbm->acquire();
		$result = $conn->create( 'SELECT "tname" FROM "mw_cache_tag_test" WHERE "tid" = \'t:3\' ORDER BY "tname"' )->execute();
		self::$dbm->release( $conn );

		$this->assertEquals( array( 'tname' => 'tag:2' ), $result->fetch() );
		$this->assertEquals( array( 'tname' => 'tag:3' ), $result->fetch() );
		$this->assertNull( $result->fetch() );


		$conn = self::$dbm->acquire();
		$result = $conn->create( 'SELECT * FROM "mw_cache_test" WHERE "id" = \'t:3\'' )->execute();
		self::$dbm->release( $conn );

		$row = $result->fetch();

		$this->assertEquals( 't:3', $row['id'] );
		$this->assertEquals( 'test 3', $row['value'] );
		$this->assertEquals( '2100-01-01 00:00:00', substr( $row['expire'], 0, 19 ) );
		$this->assertNull( $result->fetch() );
	}


	public function testSetMultiple()
	{
		$pairs = ['t:3' => 'test 3', 't:2' => 'test 4'];

		$this->assertTrue( $this->object->setMultiple( $pairs, '2100-01-01 00:00:00', ['tag:2', 'tag:3'] ) );


		$conn = self::$dbm->acquire();
		$result = $conn->create( 'SELECT "tname" FROM "mw_cache_tag_test" WHERE "tid" = \'t:3\' ORDER BY "tname"' )->execute();
		self::$dbm->release( $conn );

		$this->assertEquals( array( 'tname' => 'tag:2' ), $result->fetch() );
		$this->assertEquals( array( 'tname' => 'tag:3' ), $result->fetch() );
		$this->assertNull( $result->fetch() );


		$conn = self::$dbm->acquire();
		$result = $conn->create( 'SELECT "tname" FROM "mw_cache_tag_test" WHERE "tid" = \'t:2\' ORDER BY "tname"' )->execute();
		self::$dbm->release( $conn );

		$this->assertEquals( array( 'tname' => 'tag:2' ), $result->fetch() );
		$this->assertEquals( array( 'tname' => 'tag:3' ), $result->fetch() );
		$this->assertNull( $result->fetch() );


		$conn = self::$dbm->acquire();
		$result = $conn->create( 'SELECT * FROM "mw_cache_test" WHERE "id" = \'t:3\'' )->execute();
		self::$dbm->release( $conn );

		$row = $result->fetch();

		$this->assertEquals( 't:3', $row['id'] );
		$this->assertEquals( 'test 3', $row['value'] );
		$this->assertEquals( '2100-01-01 00:00:00', substr( $row['expire'], 0, 19 ) );
		$this->assertNull( $result->fetch() );


		$conn = self::$dbm->acquire();
		$result = $conn->create( 'SELECT * FROM "mw_cache_test" WHERE "id" = \'t:2\'' )->execute();
		self::$dbm->release( $conn );

		$row = $result->fetch();

		$this->assertEquals( 't:2', $row['id'] );
		$this->assertEquals( 'test 4', $row['value'] );
		$this->assertEquals( '2100-01-01 00:00:00', substr( $row['expire'], 0, 19 ) );
		$this->assertNull( $result->fetch() );
	}
}
