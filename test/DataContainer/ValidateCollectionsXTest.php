<?php declare(strict_types=1);

namespace DbMockLibrary\Test\DataContainer;

use DbMockLibrary\DataContainer;
use DbMockLibrary\Exceptions\AlreadyInitializedException;
use DbMockLibrary\Test\TestCase;
use ReflectionClass;
use ReflectionException;
use UnexpectedValueException;

class ValidateCollectionsXTest extends TestCase
{
    /**
     * @return void
     * @throws AlreadyInitializedException
     * @throws ReflectionException
     */
    public function test_function(): void
    {
        // prepare
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Collection \'fooBar\' does not exist');
        DataContainer::initDataContainer(['collection' => []]);
        $reflection = new ReflectionClass(DataContainer::getInstance());
        $validateCollectionsMethod = $reflection->getMethod('validateCollections');
        $validateCollectionsMethod->setAccessible(true);

        // invoke logic & test
        $validateCollectionsMethod->invoke(DataContainer::getInstance(), ['collection']);
        $validateCollectionsMethod->invoke(DataContainer::getInstance(), ['fooBar']);
    }
}
