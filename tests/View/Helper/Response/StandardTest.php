<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2026
 */


namespace Aimeos\Base\View\Helper\Response;


class StandardTest extends \PHPUnit\Framework\TestCase
{
	private $object;
	private $mock;


	private function mock()
	{
		$view = new \Aimeos\Base\View\Standard();
		$this->mock = $this->getMockBuilder( \Psr\Http\Message\ResponseInterface::class )->getMock();
		$this->object = new \Aimeos\Base\View\Helper\Response\Standard( $view, $this->mock );
	}


	public function testTransform()
	{
		$view = new \Aimeos\Base\View\Standard();
		$stub = $this->createStub( \Psr\Http\Message\ResponseInterface::class );
		$object = new \Aimeos\Base\View\Helper\Response\Standard( $view, $stub );

		$this->assertInstanceOf( \Aimeos\Base\View\Helper\Response\Iface::class, $object->transform() );
	}


	public function testCreateStream()
	{
		$view = new \Aimeos\Base\View\Standard();
		$stub = $this->createStub( \Psr\Http\Message\ResponseInterface::class );
		$object = new \Aimeos\Base\View\Helper\Response\Standard( $view, $stub );

		$this->assertInstanceOf( \Psr\Http\Message\StreamInterface::class, $object->createStream( __FILE__ ) );
	}


	public function testCreateStreamFromString()
	{
		$view = new \Aimeos\Base\View\Standard();
		$stub = $this->createStub( \Psr\Http\Message\ResponseInterface::class );
		$object = new \Aimeos\Base\View\Helper\Response\Standard( $view, $stub );

		$this->assertInstanceOf( \Psr\Http\Message\StreamInterface::class, $object->createStreamFromString( 'test' ) );
	}


	public function testGetProtocolVersion()
	{
		$this->mock();
		$this->mock->expects( $this->once() )->method( 'getProtocolVersion' )
			->willReturn( '1.0' );

		$this->assertEquals( '1.0', $this->object->getProtocolVersion() );
	}


	public function testWithProtocolVersion()
	{
		$this->mock();
		$this->mock->expects( $this->once() )->method( 'withProtocolVersion' )
			->willReturn( $this->mock );

		$this->assertEquals( $this->object, $this->object->withProtocolVersion( '1.0' ) );
	}


	public function testGetHeaders()
	{
		$this->mock();
		$this->mock->expects( $this->once() )->method( 'getHeaders' )
			->willReturn( [] );

		$this->assertEquals( [], $this->object->getHeaders() );
	}


	public function testHasHeader()
	{
		$this->mock();
		$this->mock->expects( $this->once() )->method( 'hasHeader' )
			->willReturn( true );

		$this->assertEquals( true, $this->object->hasHeader( 'test' ) );
	}


	public function testGetHeader()
	{
		$this->mock();
		$this->mock->expects( $this->once() )->method( 'getHeader' )
			->willReturn( ['value'] );

		$this->assertEquals( ['value'], $this->object->getHeader( 'test' ) );
	}


	public function testGetHeaderLine()
	{
		$this->mock();
		$this->mock->expects( $this->once() )->method( 'getHeaderLine' )
			->willReturn( 'value' );

		$this->assertEquals( 'value', $this->object->getHeaderLine( 'test' ) );
	}


	public function testWithHeader()
	{
		$this->mock();
		$this->mock->expects( $this->once() )->method( 'withHeader' )
			->willReturn( $this->mock );

		$this->assertEquals( $this->object, $this->object->withHeader( 'test', 'value' ) );
	}


	public function testWithAddedHeader()
	{
		$this->mock();
		$this->mock->expects( $this->once() )->method( 'withAddedHeader' )
			->willReturn( $this->mock );

		$this->assertEquals( $this->object, $this->object->withAddedHeader( 'test', 'value' ) );
	}


	public function testWithoutHeader()
	{
		$this->mock();
		$this->mock->expects( $this->once() )->method( 'withoutHeader' )
			->willReturn( $this->mock );

		$this->assertEquals( $this->object, $this->object->withoutHeader( 'test' ) );
	}


	public function testGetBody()
	{
		$this->mock();
		$stream = $this->createStub( \Psr\Http\Message\StreamInterface::class );

		$this->mock->expects( $this->once() )->method( 'getBody' )
			->willReturn( $stream );

		$this->assertEquals( $stream, $this->object->getBody() );
	}


	public function testWithBody()
	{
		$this->mock();
		$stream = $this->createStub( \Psr\Http\Message\StreamInterface::class );

		$this->mock->expects( $this->once() )->method( 'withBody' )
			->willReturn( $this->mock );

		$this->assertEquals( $this->object, $this->object->withBody( $stream ) );
	}


	public function testGetStatusCode()
	{
		$this->mock();
		$this->mock->expects( $this->once() )->method( 'getStatusCode' )
			->willReturn( 200 );

		$this->assertEquals( 200, $this->object->getStatusCode() );
	}


	public function testWithStatus()
	{
		$this->mock();
		$this->mock->expects( $this->once() )->method( 'withStatus' )
			->willReturn( $this->mock );

		$this->assertEquals( $this->object, $this->object->withStatus( 500, 'phrase' ) );
	}


	public function testGetReasonPhrase()
	{
		$this->mock();
		$this->mock->expects( $this->once() )->method( 'getReasonPhrase' )
			->willReturn( 'test' );

		$this->assertEquals( 'test', $this->object->getReasonPhrase() );
	}
}
