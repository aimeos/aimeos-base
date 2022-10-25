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


	public function testClone()
	{
		$object = new \Aimeos\Base\Process\Pcntl();
		$this->assertNotSame( $object, clone $object );
	}


	public function testExec()
	{
		$fcn = function() {};
		$this->assertEquals( 0, $this->access( 'exec' )->invokeArgs( new \Aimeos\Base\Process\Pcntl(), [$fcn, []] ) );
	}


	public function testExecException()
	{
		$this->expectException( \Exception::class );

		$fcn = function() { throw new \Exception(); };
		$this->access( 'exec' )->invokeArgs( new \Aimeos\Base\Process\Pcntl(), [$fcn, []] );
	}


	public function testIsAvailable()
	{
		$object = new \Aimeos\Base\Process\Pcntl();
		$this->assertTrue( $object->isAvailable() );
	}


	public function testRun()
	{
		$object = new \Aimeos\Base\Process\Pcntl( 1 );
		$fcn = function() { sleep( 1 ); };

		$start = microtime( true );
		$return = $object->start( $fcn, [\TestHelper::getConfig()] )->start( $fcn, [] )->wait();
		$msec = ( microtime( true ) - $start );

		$this->assertInstanceOf( \Aimeos\Base\Process\Iface::class, $return );
		$this->assertGreaterThan( 1, $msec );
	}


	protected function access( $name )
	{
		$class = new \ReflectionClass( \Aimeos\Base\Process\Pcntl::class );
		$method = $class->getMethod( $name );
		$method->setAccessible( true );

		return $method;
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
