<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2011
 * @copyright Aimeos (aimeos.org), 2015-2023
 */


namespace Aimeos\Base\Criteria\Expression\Sort;


class SQLTest extends \PHPUnit\Framework\TestCase
{
	private $conn = null;


	protected function setUp() : void
	{
		if( \TestHelper::getConfig()->get( 'resource/db/adapter', false ) === false ) {
			$this->markTestSkipped( 'No database configured' );
		}

		$this->conn = \TestHelper::getConnection();
	}


	public function testGetOperators()
	{
		$expected = array( '+', '-' );
		$actual = \Aimeos\Base\Criteria\Expression\Sort\SQL::getOperators();
		$this->assertEquals( $expected, $actual );
	}


	public function testGetOperator()
	{
		$expr = new \Aimeos\Base\Criteria\Expression\Sort\SQL( $this->conn, '+', 'test' );
		$this->assertEquals( '+', $expr->getOperator() );
	}


	public function testGetName()
	{
		$expr = new \Aimeos\Base\Criteria\Expression\Sort\SQL( $this->conn, '-', 'test' );
		$this->assertEquals( 'test', $expr->getName() );
	}


	public function testToString()
	{
		$types = array(
			'test' => \Aimeos\Base\DB\Statement\Base::PARAM_STR,
			'test()' => \Aimeos\Base\DB\Statement\Base::PARAM_STR,
		);

		$translations = array(
			'test()' => 'testfunc($1,$2)',
		);

		$object = new \Aimeos\Base\Criteria\Expression\Sort\SQL( $this->conn, '-', 'test' );
		$this->assertEquals( 'test DESC', $object->toSource( $types ) );

		$object = new \Aimeos\Base\Criteria\Expression\Sort\SQL( $this->conn, '+', 'test(1,2.1)' );
		$this->assertEquals( 'testfunc(1,2.1) ASC', $object->toSource( $types, $translations ) );

		$object = new \Aimeos\Base\Criteria\Expression\Sort\SQL( $this->conn, '-', 'test("a",2)' );
		$this->assertEquals( 'testfunc(\'a\',2) DESC', $object->toSource( $types, $translations ) );
	}


	public function testToArray()
	{
		$object = new \Aimeos\Base\Criteria\Expression\Sort\SQL( $this->conn, '+', 'stringvar' );

		$this->assertEquals( ['stringvar' => '+'], $object->__toArray() );
	}
}
