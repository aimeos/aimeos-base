<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2024
 */


namespace Aimeos\Base\View\Helper\Response;


class StandardTest extends \PHPUnit\Framework\TestCase
{
	private $object;
	private $response;


	protected function setUp() : void
	{
		$view = new \Aimeos\Base\View\Standard();
		$this->response = $this->getMockBuilder( \Psr\Http\Message\ResponseInterface::class )->getMock();
		$this->object = new \Aimeos\Base\View\Helper\Response\Standard( $view, $this->response );
	}


	protected function tearDown() : void
	{
		unset( $this->object, $this->response );
	}


	public function testTransform()
	{
		$this->assertInstanceOf( \Aimeos\Base\View\Helper\Response\Iface::class, $this->object->transform() );
	}


	public function testCreateStream()
	{
		$this->assertInstanceOf( \Psr\Http\Message\StreamInterface::class, $this->object->createStream( __FILE__ ) );
	}


	public function testCreateStreamFromString()
	{
		$this->assertInstanceOf( \Psr\Http\Message\StreamInterface::class, $this->object->createStreamFromString( 'test' ) );
	}


	public function testGetProtocolVersion()
	{
		$this->response->expects( $this->once() )->method( 'getProtocolVersion' )
			->willReturn( '1.0' );

		$this->assertEquals( '1.0', $this->object->getProtocolVersion() );
	}


	public function testWithProtocolVersion()
	{
		$this->response->expects( $this->once() )->method( 'withProtocolVersion' )
			->willReturn( $this->response );

		$this->assertEquals( $this->object, $this->object->withProtocolVersion( '1.0' ) );
	}


	public function testGetHeaders()
	{
		$this->response->expects( $this->once() )->method( 'getHeaders' )
			->willReturn( [] );

		$this->assertEquals( [], $this->object->getHeaders() );
	}


	public function testHasHeader()
	{
		$this->response->expects( $this->once() )->method( 'hasHeader' )
			->willReturn( true );

		$this->assertEquals( true, $this->object->hasHeader( 'test' ) );
	}


	public function testGetHeader()
	{
		$this->response->expects( $this->once() )->method( 'getHeader' )
			->willReturn( ['value'] );

		$this->assertEquals( ['value'], $this->object->getHeader( 'test' ) );
	}


	public function testGetHeaderLine()
	{
		$this->response->expects( $this->once() )->method( 'getHeaderLine' )
			->willReturn( 'value' );

		$this->assertEquals( 'value', $this->object->getHeaderLine( 'test' ) );
	}


	public function testWithHeader()
	{
		$this->response->expects( $this->once() )->method( 'withHeader' )
			->willReturn( $this->response );

		$this->assertEquals( $this->object, $this->object->withHeader( 'test', 'value' ) );
	}


	public function testWithAddedHeader()
	{
		$this->response->expects( $this->once() )->method( 'withAddedHeader' )
			->willReturn( $this->response );

		$this->assertEquals( $this->object, $this->object->withAddedHeader( 'test', 'value' ) );
	}


	public function testWithoutHeader()
	{
		$this->response->expects( $this->once() )->method( 'withoutHeader' )
			->willReturn( $this->response );

		$this->assertEquals( $this->object, $this->object->withoutHeader( 'test' ) );
	}


	public function testGetBody()
	{
		$stream = $this->getMockBuilder( \Psr\Http\Message\StreamInterface::class )->getMock();

		$this->response->expects( $this->once() )->method( 'getBody' )
			->willReturn( $stream );

		$this->assertEquals( $stream, $this->object->getBody() );
	}


	public function testWithBody()
	{
		$stream = $this->getMockBuilder( \Psr\Http\Message\StreamInterface::class )->getMock();

		$this->response->expects( $this->once() )->method( 'withBody' )
			->willReturn( $this->response );

		$this->assertEquals( $this->object, $this->object->withBody( $stream ) );
	}


	public function testGetStatusCode()
	{
		$this->response->expects( $this->once() )->method( 'getStatusCode' )
			->willReturn( 200 );

		$this->assertEquals( 200, $this->object->getStatusCode() );
	}


	public function testWithStatus()
	{
		$this->response->expects( $this->once() )->method( 'withStatus' )
			->willReturn( $this->response );

		$this->assertEquals( $this->object, $this->object->withStatus( 500, 'phrase' ) );
	}


	public function testGetReasonPhrase()
	{
		$this->response->expects( $this->once() )->method( 'getReasonPhrase' )
			->willReturn( 'test' );

		$this->assertEquals( 'test', $this->object->getReasonPhrase() );
	}
}
