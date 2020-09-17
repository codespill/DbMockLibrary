<?php declare(strict_types=1);

namespace DbMockLibrary\Test\Base;

use DbMockLibrary\Base;
use DbMockLibrary\Test\TestCase;
use UnexpectedValueException;

class GetInstanceXTest extends TestCase
{
    /**
     * @return void
     */
    public function test_function(): void
    {
        // prepare
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Not initialized');

        // invoke logic & test
        Base::getInstance();
    }
}
