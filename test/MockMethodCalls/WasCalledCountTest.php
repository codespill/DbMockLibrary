<?php declare(strict_types=1);

namespace DbMockLibrary\Test\MockMethodCalls;

use DbMockLibrary\Exceptions\AlreadyInitializedException;
use DbMockLibrary\MockMethodCalls;
use DbMockLibrary\Test\TestCase;
use ReflectionClass;
use ReflectionException;

class WasCalledCountTest extends TestCase
{
    /**
     * @dataProvider getData
     *
     * @param array $data
     *
     * @return void
     * @throws AlreadyInitializedException
     * @throws ReflectionException
     */
    public function test_function(array $data): void
    {
        // prepare
        MockMethodCalls::init();
        $traces = [
            [
                [
                    'function' => 'getMessage',
                    'class' => 'Exception',
                    'args' => ['foo']
                ]
            ],
            [
                [
                    'function' => 'getMessage',
                    'class' => 'Exception',
                    'args' => ['foo']
                ]
            ]
        ];
        $reflection = new ReflectionClass(MockMethodCalls::class);
        $traceProperty = $reflection->getProperty('traces');
        $traceProperty->setAccessible(true);
        $traceProperty->setValue(MockMethodCalls::getInstance(), $traces);

        // invoke logic
        if (isset($data['arguments'])) {
            $result = MockMethodCalls::getInstance()->wasCalledCount($data['class'], $data['method'],
                $data['arguments']);
        } else {
            $result = MockMethodCalls::getInstance()->wasCalledCount($data['class'], $data['method']);
        }

        // test
        $this->assertEquals($data['expected'], $result);
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return [
            // #0 was called at least once
            [
                [
                    'class' => 'Exception',
                    'method' => 'getMessage',
                    'arguments' => ['bar'],
                    'expected' => 0
                ]
            ],
            // #1 was not called required number of times
            [
                [
                    'class' => 'Exception',
                    'method' => 'getMessage',
                    'arguments' => ['foo'],
                    'expected' => 2
                ]
            ],
            // #1 was called required number of times
            [
                [
                    'class' => 'Exception',
                    'method' => 'getMessage',
                    'expected' => 2
                ]
            ]
        ];
    }
}
