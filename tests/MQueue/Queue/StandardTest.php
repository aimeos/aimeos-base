<?php

namespace Aimeos\Base\MQueue\Queue;


class StandardTest extends \PHPUnit\Framework\TestCase
{
	private static $conn;
	private $object;


	public static function setUpBeforeClass() : void
	{
		$schema = new \Doctrine\DBAL\Schema\Schema();

		$table = $schema->createTable( 'mw_mqueue_test' );
		$table->addColumn( 'id', 'integer', array( 'autoincrement' => true ) );
		$table->addColumn( 'queue', 'string', array( 'length' => 255 ) );
		$table->addColumn( 'cname', 'string', array( 'length' => 32 ) );
		$table->addColumn( 'rtime', 'datetime', [] );
		$table->addColumn( 'message', 'text', array( 'length' => 0xffff ) );
		$table->setPrimaryKey( array( 'id' ) );

		self::$conn = \TestHelper::getConnection();

		foreach( $schema->toSQL( self::$conn->getRawObject()->getDatabasePlatform() ) as $sql ) {
			self::$conn->create( $sql )->execute()->finish();
		}
	}


	public static function tearDownAfterClass() : void
	{
		self::$conn->create( 'DROP TABLE "mw_mqueue_test"' )->execute()->finish();
	}


	protected function setUp() : void
	{
		$config = array(
			'db' => \TestHelper::getConfig()->get( 'resource/db' ),
			'sql' => array(
				'insert' => 'INSERT INTO mw_mqueue_test (queue, cname, rtime, message) VALUES (?, ?, ?, ?)',
				'delete' => 'DELETE FROM mw_mqueue_test WHERE id = ? AND queue = ?',
			),
		);

		if( \TestHelper::getConfig()->get( 'resource/db/adapter' ) === 'mysql' )
		{
			$config['sql']['reserve'] = '
				UPDATE mw_mqueue_test SET cname = ?, rtime = ? WHERE id IN (
					SELECT id FROM (
						SELECT id FROM mw_mqueue_test WHERE queue = ? AND rtime < ? ORDER BY id LIMIT 1
					) AS t
				)
			';
			$config['sql']['get'] = '
				SELECT * FROM mw_mqueue_test WHERE queue = ? AND cname = ? AND rtime = ?
				ORDER BY id LIMIT 1
			';
		}
		else
		{
			$config['sql']['reserve'] = '
				UPDATE mw_mqueue_test SET cname = ?, rtime = ? WHERE id IN (
					SELECT id FROM (
						SELECT id FROM mw_mqueue_test WHERE queue = ? AND rtime < ?
						ORDER BY id OFFSET 0 ROWS FETCH NEXT 1 ROWS ONLY
					) AS t
				)
			';
			$config['sql']['get'] = '
				SELECT * FROM mw_mqueue_test WHERE queue = ? AND cname = ? AND rtime = ?
				ORDER BY id OFFSET 0 ROWS FETCH NEXT 1 ROWS ONLY
			';
		}

		$mqueue = new \Aimeos\Base\MQueue\Standard( $config );
		$this->object = $mqueue->getQueue( 'email' );
	}


	protected function tearDown() : void
	{
		unset( $this->object );
	}


	public function testProcess()
	{
		$this->object->add( 'test' );
		$msg = $this->object->get();

		$this->assertInstanceOf( \Aimeos\Base\MQueue\Message\Iface::class, $msg );

		$this->object->del( $msg );

		$this->assertNull( $this->object->get() );
	}


	public function testProcessMultiple()
	{
		$this->object->add( 'test1' );
		$this->object->add( 'test2' );

		$msg1 = $this->object->get();

		$this->assertInstanceOf( \Aimeos\Base\MQueue\Message\Iface::class, $msg1 );

		$this->object->del( $msg1 );

		$msg2 = $this->object->get();

		$this->assertInstanceOf( \Aimeos\Base\MQueue\Message\Iface::class, $msg2 );

		$this->object->del( $msg2 );

		$this->assertNull( $this->object->get() );
		$this->assertTrue( $msg1->getBody() !== $msg2->getBody() );
	}
}
