<?php declare(strict_types=1);

namespace DbMockLibrary\Test\MockMethodCalls;

use DbMockLibrary\Exceptions\AlreadyInitializedException;
use DbMockLibrary\MockMethodCalls;
use DbMockLibrary\Test\TestCase;
use ReflectionClass;
use ReflectionException;

class ResetDataTest extends TestCase
{
    /**
     * @return void
     * @throws AlreadyInitializedException
     * @throws ReflectionException
     */
    public function test_function(): void
    {
        // prepare
        $traces = ['foo' => 1];
        $callArguments = ['bar' => 1];
        MockMethodCalls::init();
        $reflection = new ReflectionClass(MockMethodCalls::class);
        $staticProperties = $reflection->getStaticProperties();
        $tracesProperty = $reflection->getProperty('traces');
        $tracesProperty->setAccessible(true);
        $tracesProperty->setValue($staticProperties['instance'], $traces);
        $callArgumentsProperty = $reflection->getProperty('callArguments');
        $callArgumentsProperty->setAccessible(true);
        $callArgumentsProperty->setValue($staticProperties['instance'], $callArguments);

        // invoke logic
        MockMethodCalls::getInstance()->reset();

        // test
        $this->assertEquals([], $tracesProperty->getValue($staticProperties['instance']));
        $this->assertEquals([], $callArgumentsProperty->getValue($staticProperties['instance']));
    }
}
