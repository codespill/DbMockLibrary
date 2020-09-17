<?php declare(strict_types=1);

namespace DbMockLibrary\Test\DependencyHandler;

use DbMockLibrary\DependencyHandler;
use DbMockLibrary\Exceptions\AlreadyInitializedException;
use DbMockLibrary\Test\TestCase;
use ReflectionClass;
use ReflectionException;

class RepackDependenciesTest extends TestCase
{
    /**
     * @return void
     * @throws AlreadyInitializedException
     * @throws ReflectionException
     */
    public function test_function(): void
    {
        // prepare
        $extracted = [
            [
                'a' => [
                    'a1'
                ]
            ],
            [
                'c' => [
                    'c1'
                ]
            ],
            [
                'd' => [
                    'd1',
                    'd2'
                ]
            ],
            [
                'b' => ['b1']
            ],
            [
                'd' => ['d1']
            ]
        ];
        $expected = [
            'a' => [
                'i' => 0,
                'a1'
            ],
            'c' => [
                'i' => 1,
                'c1'
            ],
            'b' => [
                'i' => 3,
                'b1'
            ],
            'd' => [
                'i' => 4,
                'd1',
                'd2',
                'd1'
            ]
        ];
        DependencyHandler::initDependencyHandler([]);
        $reflection = new ReflectionClass(DependencyHandler::class);
        $dependenciesMethod = $reflection->getMethod('repackDependencies');
        $dependenciesMethod->setAccessible(true);

        // test
        $this->assertEquals($expected, $dependenciesMethod->invoke(DependencyHandler::getInstance(), $extracted));
    }
}
