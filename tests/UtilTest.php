<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

class UtilTest extends TestCase
{
    public function testSomething(): void
    {
        $superString = "it`s for xdebug test";
        $this->assertEquals("it`s for xdebug test", "it`s for xdebug test");
    }
}
