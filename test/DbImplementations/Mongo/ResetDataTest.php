<?php declare(strict_types=1);

namespace DbMockLibrary\Test\DbImplementations\Mongo;

use DbMockLibrary\DbImplementations\Mongo;
use DbMockLibrary\Exceptions\AlreadyInitializedException;
use DbMockLibrary\Test\TestCase;
use MongoDB\Exception\InvalidArgumentException;
use ReflectionClass;
use ReflectionException;

class ResetDataTest extends TestCase
{
    /**
     * @return void
     * @throws AlreadyInitializedException
     * @throws InvalidArgumentException
     * @throws ReflectionException
     */
    public function test_function(): void
    {
        // prepare
        $dataArray = ['foo' => 1];
        Mongo::initMongo($dataArray, 'fooBar', []);
        $reflection = new ReflectionClass(Mongo::class);
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
        Mongo::getInstance()->resetData();

        // test
        $this->assertEquals($dataArray, $dataProperty->getValue($staticProperties['instance']));
    }
}
