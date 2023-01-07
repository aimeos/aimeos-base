<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2023
 */

namespace Aimeos\Base\Mail\Message;


class NoneTest extends \PHPUnit\Framework\TestCase
{
	private $object;


	protected function setUp() : void
	{
		$this->object = new \Aimeos\Base\Mail\Message\None();
	}


	protected function tearDown() : void
	{
		unset( $this->object );
	}


	public function testFrom()
	{
		$this->assertInstanceOf( \Aimeos\Base\Mail\Message\Iface::class, $this->object->from( 'test@example.com' ) );
	}


	public function testTo()
	{
		$this->assertInstanceOf( \Aimeos\Base\Mail\Message\Iface::class, $this->object->to( 'test@example.com' ) );
	}


	public function testCc()
	{
		$this->assertInstanceOf( \Aimeos\Base\Mail\Message\Iface::class, $this->object->cc( 'test@example.com' ) );
	}


	public function testBcc()
	{
		$this->assertInstanceOf( \Aimeos\Base\Mail\Message\Iface::class, $this->object->bcc( 'test@example.com' ) );
		$this->assertInstanceOf( \Aimeos\Base\Mail\Message\Iface::class, $this->object->bcc( ['test@example.com', 'a@b.com'] ) );
	}


	public function testReplyTo()
	{
		$this->assertInstanceOf( \Aimeos\Base\Mail\Message\Iface::class, $this->object->replyTo( 'test@example.com' ) );
	}


	public function testHeader()
	{
		$this->assertInstanceOf( \Aimeos\Base\Mail\Message\Iface::class, $this->object->header( 'X-Generator', 'Aimeos' ) );
	}


	public function testSend()
	{
		$this->assertInstanceOf( \Aimeos\Base\Mail\Message\Iface::class, $this->object->send() );
	}


	public function testSender()
	{
		$this->assertInstanceOf( \Aimeos\Base\Mail\Message\Iface::class, $this->object->sender( 'test@example.com' ) );
	}


	public function testSubject()
	{
		$this->assertInstanceOf( \Aimeos\Base\Mail\Message\Iface::class, $this->object->subject( 'test' ) );
	}


	public function testText()
	{
		$this->assertInstanceOf( \Aimeos\Base\Mail\Message\Iface::class, $this->object->text( 'test' ) );
	}


	public function testHtml()
	{
		$this->assertInstanceOf( \Aimeos\Base\Mail\Message\Iface::class, $this->object->html( 'test' ) );
	}


	public function testAttach()
	{
		$this->assertInstanceOf( \Aimeos\Base\Mail\Message\Iface::class, $this->object->attach( 'test' ) );
	}


	public function testEmbed()
	{
		$this->assertEquals( '', $this->object->embed( 'test' ) );
	}
}
