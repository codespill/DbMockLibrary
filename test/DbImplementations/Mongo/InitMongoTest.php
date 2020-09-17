<?php declare(strict_types=1);

namespace DbMockLibrary\Test\DbImplementations\Mongo;

use DbMockLibrary\DbImplementations\Mongo;
use DbMockLibrary\Exceptions\AlreadyInitializedException;
use DbMockLibrary\Test\TestCase;
use MongoDB\Exception\InvalidArgumentException;
use ReflectionClass;
use ReflectionException;

class InitMongoTest extends TestCase
{
    protected function tearDown(): void
    {
        Mongo::getInstance()->destroy();
    }

    /**
     * @return void
     * @throws AlreadyInitializedException
     * @throws ReflectionException
     * @throws InvalidArgumentException
     */
    public function test_function(): void
    {
        // prepare
        $dataArray = ['testCollection' => [1 => ['foo' => 1, 'id' => 1]]];

        // invoke logic
        Mongo::initMongo($dataArray, 'DbMockLibraryTest', []);

        // prepare
        $reflection = new ReflectionClass(Mongo::class);
        $staticProperties = $reflection->getStaticProperties();

        // test
        $this->assertInstanceOf(Mongo::class, $staticProperties['instance']);
        $this->assertEquals($dataArray, $staticProperties['initialData']);

        // prepare
        $dataProperty = $reflection->getProperty('data');
        $dataProperty->setAccessible(true);

        // test
        $this->assertEquals($dataArray, $dataProperty->getValue($staticProperties['instance']));
    }
}
