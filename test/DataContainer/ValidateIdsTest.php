<?php declare(strict_types=1);

namespace DbMockLibrary\Test\DataContainer;

use DbMockLibrary\DataContainer;
use DbMockLibrary\Exceptions\AlreadyInitializedException;
use DbMockLibrary\Test\TestCase;
use ReflectionException;

class ValidateIdsTest extends TestCase
{
    /**
     * @return void
     * @throws AlreadyInitializedException
     * @throws ReflectionException
     */
    public function test_function(): void
    {
        // prepare
        DataContainer::initDataContainer(['collection' => ['id' => []]]);

        // invoke logic & test
        $this->assertNull($this->invokeMethodByReflection(DataContainer::getInstance(), 'validateIds',
            ['collection', ['id']]));
    }
}
