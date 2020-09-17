<?php declare(strict_types=1);

namespace DbMockLibrary\Test\DbImplementations\Mongo;

use DbMockLibrary\DbImplementations\Mongo;
use DbMockLibrary\Exceptions\AlreadyInitializedException;
use DbMockLibrary\Test\TestCase;
use MongoDB;
use MongoDB\Client;
use MongoDB\Exception\InvalidArgumentException;
use MongoDB\Exception\UnsupportedException;
use ReflectionClass;
use ReflectionException;

class InsertTest extends TestCase
{
    protected MongoDB\Database $database;

    /**
     * @return void
     * @throws AlreadyInitializedException
     * @throws InvalidArgumentException
     * @throws UnsupportedException
     */
    protected function setUp(): void
    {
        $this->database ??= (new Client())->selectDatabase('DbMockLibraryTest');

        $this->database->dropCollection('testCollection');
        $this->database->createCollection('testCollection');

        Mongo::initMongo(['testCollection' => [1 => ['foo' => 0, '_id' => 0]]], 'DbMockLibraryTest', []);
    }

    /**
     * @return void
     * @throws InvalidArgumentException
     * @throws UnsupportedException
     */
    protected function tearDown(): void
    {
        $this->database->dropCollection('testCollection');
        $this->database->drop();

        if (Mongo::getInstance()) {
            Mongo::getInstance()->destroy();
        }
    }

    /**
     * @return void
     * @throws InvalidArgumentException
     * @throws UnsupportedException
     * @throws ReflectionException
     */
    public function test_function(): void
    {
        // prepare
        $testCollection = $this->database->selectCollection('testCollection');
        $result = iterator_to_array($testCollection->find(['_id' => 0]));
        $reflection = new ReflectionClass(Mongo::getInstance());
        $insertMethod = $reflection->getMethod('insert');
        $insertMethod->setAccessible(true);

        // test
        $this->assertCount(0, $result);

        // invoke logic
        $insertMethod->invoke(Mongo::getInstance(), 'testCollection', 1);

        // prepare
        $result = iterator_to_array($testCollection->find(['_id' => 0]));

        // test
        $this->assertCount(1, $result);
    }
}
