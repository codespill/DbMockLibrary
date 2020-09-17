<?php declare(strict_types=1);

namespace DbMockLibrary\Test\MockDataManipulation;

use DbMockLibrary\Exceptions\AlreadyInitializedException;
use DbMockLibrary\MockDataManipulation;
use DbMockLibrary\Test\TestCase;
use ReflectionException;

class DeleteRowTest extends TestCase
{
    /**
     * @return void
     * @throws AlreadyInitializedException
     * @throws ReflectionException
     */
    public function test_function(): void
    {
        // invoke logic
        $dataArray = ['collection' => ['id1' => [1], 'id2' => [2]]];
        MockDataManipulation::initDataContainer($dataArray);
        $reflection = new \ReflectionClass(MockDataManipulation::class);
        $dataProperty = $reflection->getProperty('data');
        $dataProperty->setAccessible(true);

        // invoke logic
        MockDataManipulation::getInstance()->deleteRow('collection', ['id1', 'id2']);

        // test
        $this->assertEquals(['collection' => []], $dataProperty->getValue(MockDataManipulation::getInstance()));
    }
}
