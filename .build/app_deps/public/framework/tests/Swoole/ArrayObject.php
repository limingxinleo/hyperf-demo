<?php
declare(strict_types = 1);

use Swoole\StringObject;
use Swoole\ArrayObject;

use PHPUnit\Framework\TestCase;

/**
 * @covers ArrayObject
 */
final class ArrayObjectTest extends TestCase
{
    public function testSort()
    {
        $data = _string('11, 33, 22, 44,12,32,55,23,19,23')->split(',')->sort();

        var_dump($data);

        $this->assertInstanceOf(
            ArrayObject::class,
            $data
        );
    }
}