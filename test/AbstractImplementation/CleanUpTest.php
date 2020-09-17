<?php declare(strict_types=1);

namespace DbMockLibrary\Test\AbstractImplementation;

use DbMockLibrary\MockMethodCalls;
use ReflectionException;

class CleanUpTest extends FakeTestCase
{
    /**
     * @return void
     * @throws ReflectionException
     */
    public function test_function(): void
    {
        // prepare
        $insertedIntoDb = [['collection1' => 'id1'], ['collection2' => 'id2']];
        $this->setPropertyByReflection($this->fake, 'insertedIntoDb', $insertedIntoDb);

        // invoke logic
        $this->invokeMethodByReflection($this->fake, 'cleanUp', []);

        // test
        $this->assertEquals(1, MockMethodCalls::getInstance()->wasCalledCount(
            FakeImplementation::class,
            'delete',
            ['collection1', 'id1']
        ));
        $this->assertEquals(1, MockMethodCalls::getInstance()->wasCalledCount(
            FakeImplementation::class,
            'delete',
            ['collection2', 'id2']
        ));
    }
}
