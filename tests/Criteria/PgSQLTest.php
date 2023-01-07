<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2018-2023
 */


namespace Aimeos\Base\Criteria;


class PgSQLTest extends \PHPUnit\Framework\TestCase
{
	private $object;


	protected function setUp() : void
	{
		if( \TestHelper::getConfig()->get( 'resource/db/adapter', false ) === false ) {
			$this->markTestSkipped( 'No database configured' );
		}

		$conn = \TestHelper::getConnection();
		$this->object = new \Aimeos\Base\Criteria\PgSQL( $conn );
	}


	protected function tearDown() : void
	{
		unset( $this->object );
	}


	public function testCompare()
	{
		$this->assertInstanceOf( \Aimeos\Base\Criteria\Expression\Compare\PgSQL::class, $this->object->compare( '!=', 'name', 'value' ) );
	}


	public function testGetConditionSource()
	{
		$types = array( 'column' => \Aimeos\Base\DB\Statement\Base::PARAM_BOOL );
		$this->object->setConditions( $this->object->compare( '==', 'column', 0 ) );
		$this->assertEquals( "column = 'f'", $this->object->getConditionSource( $types ) );

		$types = array( 'column' => \Aimeos\Base\DB\Statement\Base::PARAM_BOOL );
		$this->object->setConditions( $this->object->compare( '==', 'column', 1 ) );
		$this->assertEquals( "column = 't'", $this->object->getConditionSource( $types ) );
	}
}
