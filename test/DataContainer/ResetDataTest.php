<?php declare(strict_types=1);

namespace DbMockLibrary\Test\DataContainer;

use DbMockLibrary\DataContainer;
use DbMockLibrary\Exceptions\AlreadyInitializedException;
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
        $dataArray = [1];
        DataContainer::initDataContainer($dataArray);
        $reflection = new ReflectionClass(DataContainer::class);
        $staticProperties = $reflection->getStaticProperties();
        $dataProperty = $reflection->getProperty('data');
        $dataProperty->setAccessible(true);

        // test
        $this->assertEquals($dataArray, $dataProperty->getValue($staticProperties['instance']));

        // prepare
        $dataProperty->setValue($staticProperties['instance'], [2]);

        // test
        $this->assertNotEquals($dataArray, $dataProperty->getValue($staticProperties['instance']));

        // invoke logic
        DataContainer::getInstance()->resetData();

        // test
        $this->assertEquals($dataArray, $dataProperty->getValue($staticProperties['instance']));
    }
}
