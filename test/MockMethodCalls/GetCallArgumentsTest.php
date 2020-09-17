<?php declare(strict_types=1);

namespace DbMockLibrary\Test\MockMethodCalls;

use DbMockLibrary\Exceptions\AlreadyInitializedException;
use DbMockLibrary\MockMethodCalls;
use DbMockLibrary\Test\TestCase;
use ReflectionException;

class GetCallArgumentsTest extends TestCase
{
    /**
     * Test function
     *
     * @return void
     * @throws AlreadyInitializedException
     * @throws ReflectionException
     */
    public function test_function(): void
    {
        // prepare
        MockMethodCalls::init();
        $callArguments = ['fooBar'];
        $this->setPropertyByReflection(MockMethodCalls::getInstance(), 'callArguments', $callArguments);

        // invoke logic & test
        $this->assertEquals($callArguments, MockMethodCalls::getInstance()->getCallArguments());
    }
}
