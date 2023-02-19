<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 * @package Base
 * @subpackage Cache
 */


namespace Aimeos\Base\Cache;


/**
 * Database cache class.
 *
 * @package Base
 * @subpackage Cache
 */
class DB
	extends \Aimeos\Base\Cache\Base
	implements \Aimeos\Base\Cache\Iface
{
	private \Aimeos\Base\DB\Connection\Iface $conn;
	private array $sql;


	/**
	 * Initializes the object instance.
	 *
	 * The config array must contain these statement:
	 *	[delete] =>
	 *		DELETE FROM cachetable WHERE :cond
	 *	[deletebytag] =>
	 *		DELETE FROM cachetable WHERE id IN (
	 *			SELECT tid FROM cachetagtable WHERE :cond
	 *		)
	 *	[get] =>
	 *		SELECT id, value, expire FROM cachetable WHERE :cond
	 *	[set] =>
	 *		INSERT INTO cachetable ( id, expire, value ) VALUES ( ?, ?, ? )
	 *	[settag] =>
	 *		INSERT INTO cachetagtable ( tid, tname ) VALUES ( ?, ? )
	 *
	 * For using a different database connection, the name of the database connection
	 * can be also given in the "config" parameter. In this case, use e.g.
	 *  config['dbname'] = 'db-cache'
	 *
	 * @param array $config Associative list with SQL statements
	 * @param \Aimeos\Base\DB\Connection\Iface $conn Database connection
	 */
	public function __construct( array $config, \Aimeos\Base\DB\Connection\Iface $conn )
	{
		$this->sql = $config;
		$this->conn = $conn;
	}


	/**
	 * Removes all expired cache entries.
	 *
	 * @inheritDoc
	 *
	 * @return bool True on success and false on failure
	 */
	public function cleanup() : bool
	{
		try
		{
			$this->conn->create( $this->sql( 'cleanup' ) )
				->bind( 1, date( 'Y-m-d H:i:00' ) )
				->execute()->finish();
		}
		catch( \Exception $e )
		{
			return false;
		}

		return true;
	}


	/**
	 * Removes all entries of the site from the cache.
	 *
	 * @inheritDoc
	 *
	 * @return bool True on success and false on failure
	 */
	public function clear() : bool
	{
		try {
			$this->conn->create( $this->sql( 'clear' ) )->execute()->finish();
		} catch( \Exception $e ) {
			return false;
		}

		return true;
	}


	/**
	 * Removes the cache entries identified by the given keys.
	 *
	 * @inheritDoc
	 *
	 * @param iterable $keys List of key strings that identify the cache entries that should be removed
	 * @return bool True if the items were successfully removed. False if there was an error.
	 * @throws \Psr\SimpleCache\InvalidArgumentException
	 */
	public function deleteMultiple( iterable $keys ) : bool
	{
		try
		{
			if( ( $cnt = count( $keys ) ) === 0 ) {
				return true;
			}

			$pos = 1;
			$sql = $this->sql( 'delete' );
			$sql = substr_replace( $sql, str_repeat( ',?', $cnt - 1 ), strrpos( $sql, '?' ) + 1, 0 );

			$stmt = $this->conn->create( $sql );

			foreach( $keys as $key ) {
				$stmt->bind( $pos++, $key );
			}

			$stmt->execute()->finish();
		}
		catch( \Exception $e )
		{
			return false;
		}

		return true;
	}


	/**
	 * Removes the cache entries identified by the given tags.
	 *
	 * @inheritDoc
	 *
	 * @param iterable $tags List of tag strings that are associated to one or
	 *  more cache entries that should be removed
	 * @return bool True if the items were successfully removed. False if there was an error.
	 * @throws \Psr\SimpleCache\InvalidArgumentException
	 */
	public function deleteByTags( iterable $tags ) : bool
	{
		try
		{
			if( ( $cnt = count( $tags ) ) === 0 ) {
				return true;
			}

			$pos = 1;
			$sql = $this->sql( 'deletebytag' );
			$sql = substr_replace( $sql, str_repeat( ',?', $cnt - 1 ), strrpos( $sql, '?' ) + 1, 0 );

			$stmt = $this->conn->create( $sql );

			foreach( $tags as $tag ) {
				$stmt->bind( $pos++, $tag );
			}

			$stmt->execute()->finish();
		}
		catch( \Exception $e )
		{
			return false;
		}

		return true;
	}


	/**
	 * Returns the cached values for the given cache keys if available.
	 *
	 * @inheritDoc
	 *
	 * @param string[] $keys List of key strings for the requested cache entries
	 * @param mixed $default Default value to return for keys that do not exist
	 * @return iterable Associative list of key/value pairs for the requested cache
	 * 	entries. If a cache entry doesn't exist, neither its key nor a value
	 * 	will be in the result list
	 * @throws \Aimeos\Base\Cache\Exception If the cache server doesn't respond
	 */
	public function getMultiple( iterable $keys, $default = null ) : iterable
	{
		try
		{
			if( ( $cnt = count( $keys ) ) === 0 ) {
				return true;
			}

			$pos = 2;
			$list = [];

			$sql = $this->sql( 'get' );
			$sql = substr_replace( $sql, str_repeat( ',?', $cnt - 1 ), strrpos( $sql, '?' ) + 1, 0 );

			$stmt = $this->conn->create( $sql )
				->bind( 1, date( 'Y-m-d H:i:00' ) );

			foreach( $keys as $key ) {
				$stmt->bind( $pos++, $key );
			}

			$result = $stmt->execute();

			while( ( $row = $result->fetch() ) !== null ) {
				$list[$row['id']] = (string) $row['value'];
			}

			foreach( $keys as $key )
			{
				if( !isset( $list[$key] ) ) {
					$list[$key] = $default;
				}
			}
		}
		catch( \Exception $e )
		{
			return [];
		}

		return $list;
	}


	/**
	 * Determines whether an item is present in the cache.
	 *
	 * @inheritDoc
	 *
	 * @param string $key The cache item key
	 * @return bool True if cache entry is available, false if not
	 * @throws \Psr\SimpleCache\InvalidArgumentException
	 */
	public function has( string $key ) : bool
	{
		try
		{
			$return = false;
			$result = $this->conn->create( $this->sql( 'get' ) )
				->bind( 1, date( 'Y-m-d H:i:00' ) )
				->bind( 2, $key )
				->execute();

			while( $result->fetch() ) {
				$return = true;
			}
		}
		catch( \Exception $e )
		{
			return false;
		}

		return $return;
	}


	/**
	 * Adds or overwrites the given key/value pairs in the cache, which is much
	 * more efficient than setting them one by one using the set() method.
	 *
	 * @inheritDoc
	 *
	 * @param iterable $pairs Associative list of key/value pairs. Both must be a string
	 * @param \DateInterval|int|string|null $expires Date interval object,
	 *  date/time string in "YYYY-MM-DD HH:mm:ss" format or as integer TTL value
	 *  when the cache entry will expiry
	 * @param iterable $tags List of tags that should be associated to the cache entries
	 * @return bool True on success and false on failure.
	 * @throws \Psr\SimpleCache\InvalidArgumentException
	 */
	public function setMultiple( iterable $pairs, $expires = null, iterable $tags = [] ) : bool
	{
		$keys = [];
		foreach( $pairs as $key => $v ) {
			$keys[] = $key;
		}

		// Remove existing entries first to avoid duplicate key conflicts
		$this->deleteMultiple( $keys );

		try
		{
			$this->conn->begin();
			$stmt = $this->conn->create( $this->sql( 'set' ) );
			$stmtTag = $this->conn->create( $this->sql( 'settag' ) );

			foreach( $pairs as $key => $value )
			{
				if( $expires instanceof \DateInterval ) {
					$expires = date_create()->add( $expires )->format( 'Y-m-d H:i:s' );
				} elseif( is_int( $expires ) ) {
					$expires = date( 'Y-m-d H:i:s', time() + $expires );
				}

				$stmt->bind( 1, (string) $key );
				$stmt->bind( 2, $expires );
				$stmt->bind( 3, (string) $value );
				$stmt->execute()->finish();

				foreach( $tags as $name )
				{
					$stmtTag->bind( 1, (string) $key );
					$stmtTag->bind( 2, (string) $name );
					$stmtTag->execute()->finish();
				}
			}

			$this->conn->commit();
		}
		catch( \Exception $e )
		{
			$this->conn->rollback();
			return false;
		}

		return true;
	}


	/**
	 * Retturns the SQL statement for the given name.
	 *
	 * @param string $name SQL statement
	 * @throws \Aimeos\Base\Cache\Exception If SQL statement is not available
	 */
	protected function sql( string $name ) : string
	{
		if( isset( $this->sql[$name] ) ) {
			return $this->sql[$name];
		}

		throw new \Aimeos\Base\Cache\Exception( "SQL statement for $name is missing" );
	}
}
