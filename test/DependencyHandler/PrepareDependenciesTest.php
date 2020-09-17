<?php declare(strict_types=1);

namespace DbMockLibrary\Test\DependencyHandler;

use DbMockLibrary\DependencyHandler;
use DbMockLibrary\Exceptions\AlreadyInitializedException;
use DbMockLibrary\Test\TestCase;
use ReflectionClass;
use ReflectionException;

class PrepareDependenciesTest extends TestCase
{
    /**
     * @return void
     * @throws AlreadyInitializedException
     * @throws ReflectionException
     */
    public function test_function(): void
    {
        // prepare
        $wanted = ['a' => ['a1']];
        $data = [
            'a' => [
                'a1' => [
                    'aa1' => 1,
                    'aa2' => 2
                ],
                'a2' => [
                    'aa1' => 3,
                    'aa2' => 4
                ]
            ],
            'b' => [
                'b1' => [
                    'bb1' => 1,
                    'bb2' => 2
                ],
                'b2' => [
                    'bb1' => 3,
                    'bb2' => 4
                ]
            ],
            'c' => [
                'c1' => [
                    'cc1' => 1,
                    'cc2' => 2
                ],
                'c2' => [
                    'cc1' => 3,
                    'cc2' => 4
                ]
            ],
            'd' => [
                'd1' => [
                    'dd1' => 1,
                    'dd2' => 2
                ],
                'd2' => [
                    'dd1' => 3,
                    'dd2' => 2
                ]
            ]
        ];
        $dependencies = [
            [
                DependencyHandler::DEPENDENT => ['b' => 'bb1'],
                DependencyHandler::ON => ['d' => 'dd1']
            ],
            [
                DependencyHandler::DEPENDENT => ['a' => 'aa1'],
                DependencyHandler::ON => ['c' => 'cc1']
            ],
            [
                DependencyHandler::DEPENDENT => ['c' => 'cc2'],
                DependencyHandler::ON => ['d' => 'dd2']
            ],
            [
                DependencyHandler::DEPENDENT => ['a' => 'aa1'],
                DependencyHandler::ON => ['b' => 'bb1']
            ],
        ];
        $expected = [
            ['d' => ['d1', 'd2']],
            ['b' => ['b1']],
            ['c' => ['c1']],
            ['a' => ['a1']]
        ];
        DependencyHandler::initDependencyHandler($data, $dependencies);
        $reflection = new ReflectionClass(DependencyHandler::class);
        $dependenciesMethod = $reflection->getMethod('prepareDependencies');
        $dependenciesMethod->setAccessible(true);

        // test
        $this->assertEquals($expected, $dependenciesMethod->invoke(DependencyHandler::getInstance(), $wanted));
    }
}
