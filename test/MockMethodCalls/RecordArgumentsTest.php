<?php declare(strict_types=1);

namespace DbMockLibrary\Test\MockMethodCalls;

use DbMockLibrary\Exceptions\AlreadyInitializedException;
use DbMockLibrary\MockMethodCalls;
use DbMockLibrary\Test\TestCase;
use Exception;
use ReflectionClass;
use ReflectionException;

class RecordArgumentsTest extends TestCase
{
    /**
     * @return void
     * @throws AlreadyInitializedException
     * @throws ReflectionException
     */
    public function test_function(): void
    {
        // invoke logic
        MockMethodCalls::init();

        // prepare
        $reflection = new ReflectionClass(MockMethodCalls::class);
        $callArgumentsProperty = $reflection->getProperty('callArguments');
        $callArgumentsProperty->setAccessible(true);

        // test
        MockMethodCalls::getInstance()->recordArguments(new Exception(), 'getMessage', ['bar']);

        // test
        $this->assertEquals([['Exception::getMessage' => ['bar']]],
            $callArgumentsProperty->getValue(MockMethodCalls::getInstance()));
    }
}
