<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2023
 * @package Base
 * @subpackage MQueue
 */


namespace Aimeos\Base\MQueue;


/**
 * Default message queue implementation
 *
 * @package Base
 * @subpackage MQueue
 */
class Standard extends Base implements Iface
{
	private \Aimeos\Base\DB\Connection\Iface $conn;
	private array $queues = [];


	/**
	 * Initializes the message queue object
	 *
	 * @param array $config Associative list of configuration key/value pairs
	 */
	public function __construct( array $config )
	{
		parent::__construct( $config );

		try {
			$this->conn = new \Aimeos\Base\DB\Connection\PDO( $config['db'] ?? [] );
		} catch( \Aimeos\Base\DB\Exception $e ) {
			throw new \Aimeos\Base\MQueue\Exception( $e->getMessage(), -1, $e );
		}
	}


	/**
	 * Returns the queue for the given name
	 *
	 * @param string $name Queue name
	 * @return \Aimeos\Base\MQueue\Queue\Iface Message queue
	 */
	public function getQueue( string $name ) : \Aimeos\Base\MQueue\Queue\Iface
	{
		if( !isset( $this->queues[$name] ) )
		{
			$adapter = $this->config( 'db/adapter' );

			$sql = array(
				'insert' => $this->config( 'sql/insert', '
					INSERT INTO madmin_queue (queue, cname, rtime, message) VALUES (?, ?, ?, ?)
				' ),
				'reserve' => $this->config( 'sql/reserve', $adapter !== 'mysql' ? '
					UPDATE madmin_queue SET cname = ?, rtime = ? WHERE id IN (
						SELECT id FROM (
							SELECT id FROM madmin_queue WHERE queue = ? AND rtime < ?
							ORDER BY id OFFSET 0 ROWS FETCH NEXT 1 ROWS ONLY
						) AS t
					)
				' : '
					UPDATE madmin_queue SET cname = ?, rtime = ? WHERE id IN (
						SELECT id FROM (
							SELECT id FROM madmin_queue WHERE queue = ? AND rtime < ? ORDER BY id LIMIT 1
						) AS t
					)
				' ),
				'get' => $this->config( 'sql/get', $adapter !== 'mysql' ? '
					SELECT * FROM madmin_queue WHERE queue = ? AND cname = ? AND rtime = ?
					ORDER BY id OFFSET 0 ROWS FETCH NEXT 1 ROWS ONLY
				' : '
					SELECT * FROM madmin_queue WHERE queue = ? AND cname = ? AND rtime = ? ORDER BY id LIMIT 1
				' ),
				'delete' => $this->config( 'sql/delete', '
					DELETE FROM madmin_queue WHERE id = ? AND queue = ?
				' ),
			);

			$rtime = $this->config( 'releasetime', 60 );

			$this->queues[$name] = new \Aimeos\Base\MQueue\Queue\Standard( $this->conn, $name, $sql, $rtime );
		}

		return $this->queues[$name];
	}
}
