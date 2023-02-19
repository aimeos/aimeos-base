<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 * @package Base
 * @subpackage DB
 */


namespace Aimeos\Base\DB\Statement\PDO;


/**
 * Database statement class for prepared PDO statements.
 *
 * @package Base
 * @subpackage DB
 */
class Prepared extends \Aimeos\Base\DB\Statement\Base implements \Aimeos\Base\DB\Statement\Iface
{
	private array $binds = [];
	private string $sql;


	/**
	 * Initializes the statement object
	 *
	 * @param \Aimeos\Base\DB\Connection\PDO $conn Database connection object
	 * @param string $sql SQL statement
	 */
	public function __construct( \Aimeos\Base\DB\Connection\PDO $conn, string $sql )
	{
		parent::__construct( $conn );
		$this->sql = $sql;
	}


	/**
	 * Returns the SQL string as sent to the database (magic PHP method)
	 *
	 * @return string SQL statement
	 */
	public function __toString()
	{
		return $this->sql . ":\n" . print_r( array_column( $this->binds, 0 ), true );
	}


	/**
	 * Binds a value to a parameter in the statement
	 *
	 * @param int $position Position index of the placeholder
	 * @param mixed $value Value which should be bound to the placeholder
	 * @param int $type Type of given value defined in \Aimeos\Base\DB\Statement\Base as constant
	 * @return \Aimeos\Base\DB\Statement\Iface Statement instance for method chaining
	 * @throws \Aimeos\Base\DB\Exception If an error occured in the unterlying driver
	 */
	public function bind( int $position, $value, int $type = \Aimeos\Base\DB\Statement\Base::PARAM_STR ) : \Aimeos\Base\DB\Statement\Iface
	{
		$this->binds[$position] = [$value, $type];
		return $this;
	}


	/**
	 * Executes the statement
	 *
	 * @return \Aimeos\Base\DB\Result\Iface Result object
	 * @throws \Aimeos\Base\DB\Exception If an error occured in the unterlying driver
	 */
	public function execute() : \Aimeos\Base\DB\Result\Iface
	{
		try {
			$stmt = $this->exec();
		} catch( \PDOException $e ) {
			throw new \Aimeos\Base\DB\Exception( $e->getMessage() . ': ' . $this->sql . json_encode( array_column( $this->binds, 0 ) ), $e->getCode(), $e->errorInfo );
		}

		return new \Aimeos\Base\DB\Result\PDO( $stmt );
	}


	/**
	 * Binds the parameters and executes the SQL statment
	 *
	 * @return \PDOStatement Executed PDO statement
	 */
	protected function exec() : \PDOStatement
	{
		$conn = $this->getConnection();
		$stmt = $conn->getRawObject()->prepare( $this->sql );

		foreach( $this->binds as $position => $list ) {
			$stmt->bindValue( $position, $list[0], $this->getPdoType( $list[1], $list[0] ) );
		}

		try
		{
			$stmt->execute();
		}
		catch( \PDOException $e )
		{
			// recover from lost connection (MySQL)
			if( !isset( $e->errorInfo[1] ) || $e->errorInfo[1] != 2006 || $conn->inTransaction() === true ) {
				throw $e;
			}

			$conn->connect();
			return $this->exec();
		}

		return $stmt;
	}


	/**
	 * Returns the PDO type mapped to the Aimeos type
	 *
	 * @param integer $type Type of given value defined in \Aimeos\Base\DB\Statement\Base as constant
	 * @param mixed $value Value which should be bound to the placeholder
	 * @return integer PDO parameter type constant
	 * @throws \Aimeos\Base\DB\Exception If the type is unknown
	 */
	protected function getPdoType( int $type, $value ) : int
	{
		switch( $type )
		{
			case \Aimeos\Base\DB\Statement\Base::PARAM_NULL:
				$pdotype = \PDO::PARAM_NULL; break;
			case \Aimeos\Base\DB\Statement\Base::PARAM_BOOL:
				$pdotype = \PDO::PARAM_BOOL; break;
			case \Aimeos\Base\DB\Statement\Base::PARAM_INT:
				$pdotype = \PDO::PARAM_INT; break;
			case \Aimeos\Base\DB\Statement\Base::PARAM_FLOAT:
				$pdotype = \PDO::PARAM_STR; break;
			case \Aimeos\Base\DB\Statement\Base::PARAM_STR:
				$pdotype = \PDO::PARAM_STR; break;
			case \Aimeos\Base\DB\Statement\Base::PARAM_LOB:
				$pdotype = \PDO::PARAM_LOB; break;
			default:
				throw new \Aimeos\Base\DB\Exception( sprintf( 'Invalid parameter type "%1$s"', $type ) );
		}

		if( is_null( $value ) ) {
			$pdotype = \PDO::PARAM_NULL;
		}

		return $pdotype;
	}
}
