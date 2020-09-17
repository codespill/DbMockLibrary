<?php declare(strict_types=1);

namespace DbMockLibrary\Test\DataContainer;

use DbMockLibrary\DataContainer;
use DbMockLibrary\Exceptions\AlreadyInitializedException;
use DbMockLibrary\Test\TestCase;
use ReflectionClass;
use ReflectionException;

class InitDataContainerTest extends TestCase
{
    /**
     * @return void
     * @throws AlreadyInitializedException
     * @throws ReflectionException
     */
    public function test_function(): void
    {
        // invoke logic
        $dataArray = [1];
        DataContainer::initDataContainer($dataArray);

        // prepare
        $reflection = new ReflectionClass(DataContainer::class);
        $staticProperties = $reflection->getStaticProperties();

        // test
        $this->assertInstanceOf(DataContainer::class, $staticProperties['instance']);
        $this->assertEquals($dataArray, $staticProperties['initialData']);

        // prepare
        $dataProperty = $reflection->getProperty('data');
        $dataProperty->setAccessible(true);

        // test
        $this->assertEquals($dataArray, $dataProperty->getValue($staticProperties['instance']));
    }
}
