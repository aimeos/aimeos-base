<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2026
 */


namespace Aimeos\Base\View\Helper\Request;


class StandardTest extends \PHPUnit\Framework\TestCase
{
	private $object;
	private $mock;


	private function mock()
	{
		$view = new \Aimeos\Base\View\Standard();
		$this->mock = $this->getMockBuilder( \Psr\Http\Message\ServerRequestInterface::class )->getMock();
		$this->object = new \Aimeos\Base\View\Helper\Request\Standard( $view, $this->mock, '127.0.0.1', 'test' );
	}


	public function testTransform()
	{
		$view = new \Aimeos\Base\View\Standard();
		$stub = $this->createStub( \Psr\Http\Message\ServerRequestInterface::class );
		$object = new \Aimeos\Base\View\Helper\Request\Standard( $view, $stub, '127.0.0.1', 'test' );

		$this->assertInstanceOf( \Aimeos\Base\View\Helper\Request\Iface::class, $object->transform() );
	}


	public function testGetClientAddress()
	{
		$view = new \Aimeos\Base\View\Standard();
		$stub = $this->createStub( \Psr\Http\Message\ServerRequestInterface::class );
		$object = new \Aimeos\Base\View\Helper\Request\Standard( $view, $stub, '127.0.0.1', 'test' );

		$this->assertEquals( '127.0.0.1', $object->transform()->getClientAddress() );
	}


	public function testGetTarget()
	{
		$view = new \Aimeos\Base\View\Standard();
		$stub = $this->createStub( \Psr\Http\Message\ServerRequestInterface::class );
		$object = new \Aimeos\Base\View\Helper\Request\Standard( $view, $stub, '127.0.0.1', 'test' );

		$this->assertEquals( 'test', $object->transform()->getTarget() );
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


	public function testGetRequestTarget()
	{
		$this->mock();
		$this->mock->expects( $this->once() )->method( 'getRequestTarget' )
			->willReturn( 'test' );

		$this->assertEquals( 'test', $this->object->getRequestTarget() );
	}


	public function testWithRequestTarget()
	{
		$this->mock();
		$this->mock->expects( $this->once() )->method( 'withRequestTarget' )
			->willReturn( $this->mock );

		$this->assertEquals( $this->object, $this->object->withRequestTarget( 'test' ) );
	}


	public function testGetMethod()
	{
		$this->mock();
		$this->mock->expects( $this->once() )->method( 'getMethod' )
			->willReturn( 'test' );

		$this->assertEquals( 'test', $this->object->getMethod() );
	}


	public function testWithMethod()
	{
		$this->mock();
		$this->mock->expects( $this->once() )->method( 'withMethod' )
			->willReturn( $this->mock );

		$this->assertEquals( $this->object, $this->object->withMethod( 'test' ) );
	}


	public function testGetUri()
	{
		$this->mock();
		$uri = $this->createStub( \Psr\Http\Message\UriInterface::class );

		$this->mock->expects( $this->once() )->method( 'getUri' )
			->willReturn( $uri );

		$this->assertEquals( $uri, $this->object->getUri() );
	}


	public function testWithUri()
	{
		$this->mock();
		$uri = $this->createStub( \Psr\Http\Message\UriInterface::class );

		$this->mock->expects( $this->once() )->method( 'withUri' )
			->willReturn( $this->mock );

		$this->assertEquals( $this->object, $this->object->withUri( $uri, false ) );
	}


	public function testGetServerParams()
	{
		$this->mock();
		$this->mock->expects( $this->once() )->method( 'getServerParams' )
			->willReturn( [] );

		$this->assertEquals( [], $this->object->getServerParams() );
	}


	public function testGetCookieParams()
	{
		$this->mock();
		$this->mock->expects( $this->once() )->method( 'getCookieParams' )
			->willReturn( [] );

		$this->assertEquals( [], $this->object->getCookieParams() );
	}


	public function testWithCookieParams()
	{
		$this->mock();
		$this->mock->expects( $this->once() )->method( 'withCookieParams' )
			->willReturn( $this->mock );

		$this->assertEquals( $this->object, $this->object->withCookieParams( [] ) );
	}


	public function testGetQueryParams()
	{
		$this->mock();
		$this->mock->expects( $this->once() )->method( 'getQueryParams' )
			->willReturn( [] );

		$this->assertEquals( [], $this->object->getQueryParams() );
	}


	public function testWithQueryParams()
	{
		$this->mock();
		$this->mock->expects( $this->once() )->method( 'withQueryParams' )
			->willReturn( $this->mock );

		$this->assertEquals( $this->object, $this->object->withQueryParams( [] ) );
	}


	public function testGetUploadedFiles()
	{
		$this->mock();
		$this->mock->expects( $this->once() )->method( 'getUploadedFiles' )
			->willReturn( [] );

		$this->assertEquals( [], $this->object->getUploadedFiles() );
	}


	public function testWithUploadedFiles()
	{
		$this->mock();
		$this->mock->expects( $this->once() )->method( 'withUploadedFiles' )
			->willReturn( $this->mock );

		$this->assertEquals( $this->object, $this->object->withUploadedFiles( [] ) );
	}


	public function testGetParsedBody()
	{
		$this->mock();
		$this->mock->expects( $this->once() )->method( 'getParsedBody' )
			->willReturn( 'test' );

		$this->assertEquals( 'test', $this->object->getParsedBody() );
	}


	public function testWithParsedBody()
	{
		$this->mock();
		$this->mock->expects( $this->once() )->method( 'withParsedBody' )
			->willReturn( $this->mock );

		$this->assertEquals( $this->object, $this->object->withParsedBody( array( 'test' ) ) );
	}


	public function testGetAttributes()
	{
		$this->mock();
		$this->mock->expects( $this->once() )->method( 'getAttributes' )
			->willReturn( [] );

		$this->assertEquals( [], $this->object->getAttributes() );
	}


	public function testGetAttribute()
	{
		$this->mock();
		$this->mock->expects( $this->once() )->method( 'getAttribute' )
			->willReturn( 'value' );

		$this->assertEquals( 'value', $this->object->getAttribute( 'test', 'default' ) );
	}


	public function testWithAttribute()
	{
		$this->mock();
		$this->mock->expects( $this->once() )->method( 'withAttribute' )
			->willReturn( $this->mock );

		$this->assertEquals( $this->object, $this->object->withAttribute( 'test', 'value' ) );
	}


	public function testWithoutAttribute()
	{
		$this->mock();
		$this->mock->expects( $this->once() )->method( 'withoutAttribute' )
			->willReturn( $this->mock );

		$this->assertEquals( $this->object, $this->object->withoutAttribute( 'test' ) );
	}
}
