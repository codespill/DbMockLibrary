<?php declare(strict_types=1);

namespace DbMockLibrary\Test\MockMethodCalls;

use DbMockLibrary\Exceptions\AlreadyInitializedException;
use DbMockLibrary\MockMethodCalls;
use DbMockLibrary\Test\TestCase;
use stdClass;
use UnexpectedValueException;

class WasCalledCountXTest extends TestCase
{
    /**
     * @return void
     * @throws AlreadyInitializedException
     */
    public function test_function(): void
    {
        // prepare
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Invalid method');
        MockMethodCalls::init();

        // invoke logic & test
        MockMethodCalls::getInstance()->wasCalledCount(new stdClass(), 'fooBar');
    }
}
