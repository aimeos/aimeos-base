<?php

namespace Aimeos\Base\Process;


class PcntlTest extends \PHPUnit\Framework\TestCase
{
	protected function setUp() : void
	{
		if( function_exists( 'pcntl_fork' ) === false ) {
			$this->markTestSkipped( 'PCNTL extension not available' );
		}
	}


	public function testIsAvailable()
	{
		$object = new \Aimeos\Base\Process\Pcntl();
		$this->assertTrue( $object->isAvailable() );
	}


	public function testRun()
	{
		$object = new \Aimeos\Base\Process\Pcntl();
		$fcn = function() { sleep( 1 ); };

		$start = microtime( true );
		$return = $object->start( $fcn, [] )->start( $fcn, [] )->wait();
		$msec = ( microtime( true ) - $start );

		$this->assertInstanceOf( \Aimeos\Base\Process\Iface::class, $return );
		$this->assertGreaterThan( 1, $msec );
	}


	public function testRunError()
	{
		$fcn = function() { throw new \Exception(); };

		stream_filter_register( "redirect", "\Aimeos\Base\Process\DiscardFilter" );
		$filter = stream_filter_prepend( STDERR, "redirect", STREAM_FILTER_WRITE );

		$object = new \Aimeos\Base\Process\Pcntl();
		$result = $object->start( $fcn, [], true )->wait();

		stream_filter_remove( $filter );

		$this->assertInstanceOf( \Aimeos\Base\Process\Iface::class, $result );
	}
}



class DiscardFilter extends \php_user_filter
{
	public function filter( $in, $out, &$consumed, $closing ) : int
	{
		while( $bucket = stream_bucket_make_writeable( $in ) )
		{
			$bucket->data = '';
			$consumed += $bucket->datalen;
			stream_bucket_append( $out, $bucket );
		}
		return PSFS_PASS_ON;
	}
}
