<?php declare(strict_types=1);

namespace DbMockLibrary\Test\DependencyHandler;

use DbMockLibrary\DependencyHandler;
use DbMockLibrary\Exceptions\AlreadyInitializedException;
use DbMockLibrary\Test\TestCase;
use ReflectionClass;
use ReflectionException;

class InitDependencyHandlerTest extends TestCase
{
    /**
     * @return void
     * @throws AlreadyInitializedException
     * @throws ReflectionException
     */
    public function test_function(): void
    {
        // invoke logic
        $dataArray = [
            'foo1' => [
                'bar1' => [
                    'baz1' => 1
                ]
            ],
            'foo2' => [
                'bar2' => [
                    'baz2' => 2
                ]
            ]
        ];
        $dependencies = [
            [
                DependencyHandler::DEPENDENT => ['foo1' => 'baz1'],
                DependencyHandler::ON => ['foo2' => 'baz2']
            ]
        ];
        DependencyHandler::initDependencyHandler($dataArray, $dependencies);

        // prepare
        $reflection = new ReflectionClass(DependencyHandler::class);
        $staticProperties = $reflection->getStaticProperties();
        $dependenciesProperty = $reflection->getProperty('dependencies');
        $dependenciesProperty->setAccessible(true);

        // test
        $this->assertInstanceOf(DependencyHandler::class, $staticProperties['instance']);
        $this->assertEquals($dependencies, $dependenciesProperty->getValue(DependencyHandler::getInstance()));
    }
}
