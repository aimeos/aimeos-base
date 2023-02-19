<?php


/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2023
 * @package Base
 * @subpackage Translation
 */


namespace Aimeos\Base\Translation\File;


/**
 * Class for reading Gettext MO files
 *
 * @package Base
 * @subpackage Translation
 */
class Mo
{
	const MAGIC1 = -1794895138;
	const MAGIC2 = -569244523;
	const MAGIC3 = 2500072158;


	private string $str;
	private int $strlen;
	private int $pos = 0;
	private array $messages = [];


	/**
	 * Initializes the .mo file reader
	 *
	 * @param string $filepath Absolute path to the Gettext .mo file
	 */
	public function __construct( string $filepath )
	{
		if( ( $str = @file_get_contents( $filepath ) ) === false ) {
			throw new \Aimeos\Base\Translation\Exception( sprintf( 'Unable to read from file "%1$s"', $filepath ) );
		}

		$this->str = $str;
		$this->strlen = strlen( $str );
		$this->messages = $this->extract();
	}


	/**
	 * Returns all translations
	 *
	 * @return array List of translations with original as key and translations as values
	 */
	public function all() : array
	{
		return $this->messages;
	}


	/**
	 * Returns the translations for the given original string
	 *
	 * @param string $original Untranslated string
	 * @return array|string|null List of translations or false if none is available
	 */
	public function get( string $original )
	{
		return $this->messages[$original] ?? null;
	}


	/**
	 * Extracts the messages and translations from the MO file
	 *
	 * @return array Associative list of original singular as keys and one or more translations as values
	 * @throws \Aimeos\Base\Translation\Exception If file content is invalid
	 */
	protected function extract() : array
	{
		$magic = $this->readInt( 'V' );

		if( ( $magic === self::MAGIC1 ) || ( $magic === self::MAGIC3 ) ) { //to make sure it works for 64-bit platforms
			$byteOrder = 'V'; //low endian
		} elseif( $magic === ( self::MAGIC2 & 0xFFFFFFFF ) ) {
			$byteOrder = 'N'; //big endian
		} else {
			throw new \Aimeos\Base\Translation\Exception( 'Invalid MO file' );
		}

		$this->readInt( $byteOrder );
		$total = $this->readInt( $byteOrder ); //total string count
		$originals = $this->readInt( $byteOrder ); //offset of original table
		$trans = $this->readInt( $byteOrder ); //offset of translation table

		$this->seekto( (int) $originals );
		$originalTable = $this->readIntArray( $byteOrder, $total * 2 );
		$this->seekto( (int) $trans );
		$translationTable = $this->readIntArray( $byteOrder, $total * 2 );

		return $this->extractTable( $originalTable, $translationTable, (int) $total );
	}


	/**
	 * Extracts the messages and their translations
	 *
	 * @param array $originalTable MO table for original strings
	 * @param array $translationTable MO table for translated strings
	 * @param int $total Total number of translations
	 * @return array Associative list of original singular as keys and one or more translations as values
	 */
	protected function extractTable( array $originalTable, array $translationTable, int $total ) : array
	{
		$messages = [];

		for( $i = 0; $i < $total; ++$i )
		{
			$plural = null;
			$next = $i * 2;

			$this->seekto( $originalTable[$next + 2] );
			$original = $this->read( $originalTable[$next + 1] );
			$this->seekto( $translationTable[$next + 2] );
			$translated = $this->read( $translationTable[$next + 1] );

			if( $original === '' || $translated === '' ) { // Headers
				continue;
			}

			if( strpos( $original, "\x04" ) !== false ) {
				list( $context, $original ) = explode( "\x04", $original, 2 );
			}

			if( strpos( $original, "\000" ) !== false ) {
				list( $original, $plural ) = explode( "\000", $original );
			}

			if( $plural === null )
			{
				$messages[$original] = $translated;
				continue;
			}

			$messages[$original] = [];

			foreach( explode( "\x00", $translated ) as $idx => $value ) {
				$messages[$original][$idx] = $value;
			}
		}

		return $messages;
	}


	/**
	 * Returns a single integer starting from the current position
	 *
	 * @param string $byteOrder Format code for unpack()
	 * @return integer Read integer
	 */
	protected function readInt( string $byteOrder ) : ?int
	{
		if( ( $content = $this->read( 4 ) ) === '' ) {
			return null;
		}

		$content = unpack( $byteOrder, $content );
		return array_shift( $content );
	}


	/**
	 * Returns the list of integers starting from the current position
	 *
	 * @param string $byteOrder Format code for unpack()
	 * @param int $count Number of four byte integers to read
	 * @return array List of integers
	 */
	protected function readIntArray( string $byteOrder, int $count ) : array
	{
		return unpack( $byteOrder . $count, $this->read( 4 * $count ) );
	}


	/**
	 * Returns a part of the file
	 *
	 * @param int $bytes Number of bytes to read
	 * @return string Read bytes or empty on failure
	 */
	protected function read( int $bytes ) : string
	{
		$data = substr( $this->str, $this->pos, $bytes );

		if( $data !== false && $data !== '' ) {
			$this->seekto( $this->pos + $bytes );
		}

		return (string) $data;
	}


	/**
	 * Move the cursor to the position in the file
	 *
	 * @param int $pos Number of bytes to move
	 * @return int New file position in bytes
	 */
	protected function seekto( int $pos ) : int
	{
		$this->pos = ( $this->strlen < $pos ? $this->strlen : $pos );
		return $this->pos;
	}
}
