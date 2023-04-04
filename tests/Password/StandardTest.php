<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2021-2023
 */


namespace Aimeos\Base\Password;


class StandardTest extends \PHPUnit\Framework\TestCase
{
	private $object;


	protected function setUp() : void
	{
		$this->object = new \Aimeos\Base\Password\Standard();
	}


	protected function tearDown() : void
	{
		unset( $this->object );
	}


	public function testHash()
	{
		$this->assertStringStartsWith( '$2y$10$', $this->object->hash( 'unittest' ) );
	}


	public function testVerify()
	{
		$this->assertTrue( $this->object->verify( 'unittest', $this->object->hash( 'unittest' ) ) );
	}
}
