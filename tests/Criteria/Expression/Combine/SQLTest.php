<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2011
 * @copyright Aimeos (aimeos.org), 2015-2023
 */


namespace Aimeos\Base\Criteria\Expression\Combine;


class SQLTest extends \PHPUnit\Framework\TestCase
{
	protected function setUp() : void
	{
		if( \TestHelper::getConfig()->get( 'resource/db/adapter', false ) === false ) {
			$this->markTestSkipped( 'No database configured' );
		}
	}


	public function testGetOperators()
	{
		$expected = array( '&&', '||', '!' );
		$actual = \Aimeos\Base\Criteria\Expression\Combine\SQL::getOperators();
		$this->assertEquals( $expected, $actual );
	}


	public function testGetOperator()
	{
		$expr = new \Aimeos\Base\Criteria\Expression\Combine\SQL( '||', [] );
		$this->assertEquals( '||', $expr->getOperator() );
	}


	public function testGetExpressions()
	{
		$expr = new \Aimeos\Base\Criteria\Expression\Combine\SQL( '||', [] );
		$this->assertEquals( [], $expr->getExpressions() );
	}


	public function testToString()
	{
		$conn = \TestHelper::getConnection();

		$types = array(
			'list' => \Aimeos\Base\DB\Statement\Base::PARAM_STR,
			'string' => \Aimeos\Base\DB\Statement\Base::PARAM_STR,
			'float' => \Aimeos\Base\DB\Statement\Base::PARAM_FLOAT,
			'int' => \Aimeos\Base\DB\Statement\Base::PARAM_INT,
			'undefined' => \Aimeos\Base\DB\Statement\Base::PARAM_INT,
			'bool' => \Aimeos\Base\DB\Statement\Base::PARAM_BOOL,
		);

		$expr1 = [];
		$expr1[] = new \Aimeos\Base\Criteria\Expression\Compare\SQL( $conn, '==', 'list', array( 'a', 'b', 'c' ) );
		$expr1[] = new \Aimeos\Base\Criteria\Expression\Compare\SQL( $conn, '~=', 'string', 'value' );

		$expr2 = [];
		$expr2[] = new \Aimeos\Base\Criteria\Expression\Compare\SQL( $conn, '<', 'float', 0.1 );
		$expr2[] = new \Aimeos\Base\Criteria\Expression\Compare\SQL( $conn, '>', 'int', 10 );

		$objects = [];
		$objects[] = new \Aimeos\Base\Criteria\Expression\Combine\SQL( '&&', $expr1 );
		$objects[] = new \Aimeos\Base\Criteria\Expression\Combine\SQL( '&&', $expr2 );

		$object = new \Aimeos\Base\Criteria\Expression\Combine\SQL( '||', $objects );
		$test = new \Aimeos\Base\Criteria\Expression\Combine\SQL( '!', array( $object ) );

		$expected = " NOT ( ( ( list IN ('a','b','c') AND string LIKE '%value%' ESCAPE '#' ) OR ( float < 0.1 AND int > 10 ) ) )";
		$this->assertEquals( $expected, $test->toSource( $types ) );

		$obj = new \Aimeos\Base\Criteria\Expression\Combine\SQL( '&&', [] );
		$this->assertEquals( '', $obj->toSource( $types ) );

		$this->expectException( \Aimeos\Base\Exception::class );
		new \Aimeos\Base\Criteria\Expression\Combine\SQL( '', [] );
	}


	public function testToArray()
	{
		$conn = \TestHelper::getConnection();

		$expected = [
			'&&' => [
				['==' => ['stringvar' => 'value']],
				['>' => ['intvar' => 10]],
			]
		];

		$expr = [
			new \Aimeos\Base\Criteria\Expression\Compare\SQL( $conn, '==', 'stringvar', 'value' ),
			new \Aimeos\Base\Criteria\Expression\Compare\SQL( $conn, '>', 'intvar', 10 ),
		];

		$object = new \Aimeos\Base\Criteria\Expression\Combine\SQL( '&&', $expr );

		$this->assertEquals( $expected, $object->__toArray() );
	}
}
