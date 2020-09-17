<?php declare(strict_types=1);

namespace DbMockLibrary\Test\Base;

use DbMockLibrary\Base;
use DbMockLibrary\Exceptions\AlreadyInitializedException;
use DbMockLibrary\Test\TestCase;

class InitXTest extends TestCase
{
    /**
     * @throws AlreadyInitializedException
     */
    public function test_function(): void
    {
        // prepare
        $this->expectException(AlreadyInitializedException::class);
        $this->expectExceptionMessage('DbMockLibrary\Base has already been initialized');

        // invoke logic
        Base::init();
        Base::init();
    }
}
