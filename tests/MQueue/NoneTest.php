<?php

namespace Aimeos\Base\MQueue;


class NoneTest extends \PHPUnit\Framework\TestCase
{
	public function testGetQueue()
	{
		$mqueue = new \Aimeos\Base\MQueue\None( [] );

		$this->expectException( \Aimeos\Base\MQueue\Exception::class );
		$mqueue->getQueue( '' );
	}
}
