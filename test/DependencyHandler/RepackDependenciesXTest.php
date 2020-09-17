<?php declare(strict_types=1);

namespace DbMockLibrary\Test\DependencyHandler;

use DbMockLibrary\DependencyHandler;
use DbMockLibrary\Exceptions\AlreadyInitializedException;
use DbMockLibrary\Test\TestCase;
use ReflectionClass;
use ReflectionException;
use UnexpectedValueException;

class RepackDependenciesXTest extends TestCase
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
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Invalid input');
        DependencyHandler::initDependencyHandler([]);
        $reflection = new ReflectionClass(DependencyHandler::class);
        $dependenciesMethod = $reflection->getMethod('repackDependencies');
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
            // #0 dimension 2, should be 3
            [
                [
                    'data' => [[]]
                ]
            ],
            // #1 dimension 4, should be 3
            [
                [
                    'data' => [[[[]]]]
                ]
            ]
        ];
    }
}
