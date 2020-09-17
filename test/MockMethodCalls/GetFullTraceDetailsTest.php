<?php declare(strict_types=1);

namespace DbMockLibrary\Test\MockMethodCalls;

use DbMockLibrary\Exceptions\AlreadyInitializedException;
use DbMockLibrary\MockMethodCalls;
use DbMockLibrary\Test\TestCase;
use ReflectionClass;
use ReflectionException;

class GetFullTraceDetailsTest extends TestCase
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
                    'args' => ['fooBar'],
                    'foo' => 'bar'
                ]
            ]
        ];
        $reflection = new ReflectionClass(MockMethodCalls::class);
        $traceProperty = $reflection->getProperty('traces');
        $traceProperty->setAccessible(true);
        $traceProperty->setValue(MockMethodCalls::getInstance(), $traces);
        $getFullTraceDetailsMethod = $reflection->getMethod('getFullTraceDetails');
        $getFullTraceDetailsMethod->setAccessible(true);

        // invoke logic
        $result = $getFullTraceDetailsMethod->invoke(MockMethodCalls::getInstance(), $data['class'], $data['method']);

        // test
        $this->assertEquals($data['expected'], $result);
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return [
            // #0 method was called
            [
                [
                    'class' => 'Exception',
                    'method' => 'getMessage',
                    'expected' => [
                        [
                            [
                                'function' => 'getMessage',
                                'class' => 'Exception',
                                'args' => ['fooBar']
                            ]
                        ]
                    ]
                ]
            ],
            // #1 method wasn't called
            [
                [
                    'class' => 'Exception',
                    'method' => 'getTrace',
                    'expected' => []
                ]
            ]
        ];
    }
}
