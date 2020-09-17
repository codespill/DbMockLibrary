<?php declare(strict_types=1);

namespace DbMockLibrary\Test\DependencyHandler;

use DbMockLibrary\DependencyHandler;
use DbMockLibrary\Exceptions\AlreadyInitializedException;
use DbMockLibrary\Exceptions\InvalidDependencyException;
use DbMockLibrary\Test\TestCase;
use ReflectionClass;
use ReflectionException;
use UnexpectedValueException;

class CompactDependenciesXTest extends TestCase
{
    /**
     * @dataProvider getData
     *
     * @param array $data
     *
     * @return void
     * @throws ReflectionException
     * @throws AlreadyInitializedException
     */
    public function test_function(array $data): void
    {
        // prepare
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Invalid input');
        DependencyHandler::initDependencyHandler([]);
        $reflection = new ReflectionClass(DependencyHandler::class);
        $dependenciesMethod = $reflection->getMethod('compactDependencies');
        $dependenciesMethod->setAccessible(true);

        // invoke logic & test
        $dependenciesMethod->invoke(DependencyHandler::getInstance(), $data['data']);
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return [
            // #0 dimension 1, should be 2
            [
                [
                    'data' => []
                ]
            ],
            // #1 dimension 3, should be 2
            [
                [
                    'data' => [[[]]]
                ]
            ]
        ];
    }
}
