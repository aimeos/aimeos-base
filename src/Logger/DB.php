<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 * @package Base
 * @subpackage Logger
 */


namespace Aimeos\Base\Logger;


/**
 * Log messages to a database table.
 *
 * @package Base
 * @subpackage Logger
 */
class DB implements Iface
{
	use Traits;


	private \Aimeos\Base\DB\Statement\Iface $stmt;
	private string $requestid;
	private ?array $facilities;
	private int $loglevel;


	/**
	 * Initializes the logger object.
	 *
	 * The log statement must be like:
	 *		INSERT INTO logtable (facility, logtime, priority, message, requestid) VALUES (?, ?, ?, ?, ?)
	 *
	 * @param \Aimeos\Base\DB\Statement\Iface $stmt Database statement object for inserting data
	 * @param int $loglevel Minimum priority for logging
	 * @param string[]|null $facilities Facilities for which messages should be logged
	 * @param string|null $requestid Unique identifier to identify multiple log entries for the same request faster
	 */
	public function __construct( \Aimeos\Base\DB\Statement\Iface $stmt, int $loglevel = Iface::ERR,
		array $facilities = null, string $requestid = null )
	{
		$this->stmt = $stmt;
		$this->loglevel = $loglevel;
		$this->facilities = $facilities;
		$this->requestid = $requestid ?: md5( php_uname( 'n' ) . getmypid() . date( 'Y-m-d H:i:s' ) );
	}


	/**
	 * Writes a message to the configured log facility.
	 *
	 * @param string|array|object $message Message text that should be written to the log facility
	 * @param int $prio Priority of the message for filtering
	 * @param string $facility Facility for logging different types of messages (e.g. message, auth, user, changelog)
	 * @return \Aimeos\Base\Logger\Iface Logger object for method chaining
	 * @throws \Aimeos\Base\Logger\Exception If the priority is invalid
	 * @throws \Aimeos\Base\DB\Exception If an error occurs while adding log message
	 * @see \Aimeos\Base\Logger\Base for available log level constants
	 */
	public function log( $message, int $prio = Iface::ERR, string $facility = 'message' ) : Iface
	{
		if( $prio <= $this->loglevel && ( $this->facilities === null || in_array( $facility, $this->facilities ) ) )
		{
			$this->getLogLevel( $prio );

			if( !is_scalar( $message ) ) {
				$message = json_encode( $message );
			}

			$this->stmt->bind( 1, $facility );
			$this->stmt->bind( 2, date( 'Y-m-d H:i:s' ) );
			$this->stmt->bind( 3, $prio, \Aimeos\Base\DB\Statement\Base::PARAM_INT );
			$this->stmt->bind( 4, $message );
			$this->stmt->bind( 5, $this->requestid );
			$this->stmt->execute()->finish();
		}

		return $this;
	}
}
