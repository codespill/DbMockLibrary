<?php declare(strict_types=1);

namespace DbMockLibrary\Test\MockMethodCalls;

use DbMockLibrary\Exceptions\AlreadyInitializedException;
use DbMockLibrary\MockMethodCalls;
use DbMockLibrary\Test\TestCase;
use ReflectionClass;
use ReflectionException;
use UnexpectedValueException;

class GetFullTraceDetailsXTest extends TestCase
{
    /**
     * @return void
     * @throws AlreadyInitializedException
     * @throws ReflectionException
     */
    public function test_function(): void
    {
        // prepare
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Invalid method');
        MockMethodCalls::init();
        $reflection = new ReflectionClass(MockMethodCalls::getInstance());
        $validateCollectionsMethod = $reflection->getMethod('getFullTraceDetails');
        $validateCollectionsMethod->setAccessible(true);

        // invoke logic & test
        $validateCollectionsMethod->invoke(MockMethodCalls::getInstance(), 'none', 'fooBar');
    }
}
