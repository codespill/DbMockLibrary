<?php declare(strict_types=1);

namespace DbMockLibrary\Test\MockMethodCalls;

use DbMockLibrary\Exceptions\AlreadyInitializedException;
use DbMockLibrary\MockMethodCalls;
use DbMockLibrary\Test\TestCase;
use ReflectionClass;
use ReflectionException;

class RecordTraceTest extends TestCase
{
    /**
     * @return void
     *
     * @throws AlreadyInitializedException
     * @throws ReflectionException
     */
    public function test_function(): void
    {
        // prepare
        MockMethodCalls::init();
        $reflection = new ReflectionClass(MockMethodCalls::class);
        $traceProperty = $reflection->getProperty('traces');
        $traceProperty->setAccessible(true);

        // test
        MockMethodCalls::getInstance()->recordTrace();

        // prepare
        $trace = $traceProperty->getValue(MockMethodCalls::getInstance());

        // test
        foreach (['function' => 'test_function', 'class' => RecordTraceTest::class] as $key => $value) {
            $this->assertArrayHasKey($key, $trace[0][0]);
            $this->assertSame($value, $trace[0][0][$key]);
        }
    }
}
