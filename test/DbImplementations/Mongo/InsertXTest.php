<?php declare(strict_types=1);

namespace DbMockLibrary\Test\DbImplementations\Mongo;

use DbMockLibrary\DbImplementations\Mongo;
use DbMockLibrary\Exceptions\DbOperationFailedException;
use DbMockLibrary\Test\TestCase;
use Mockery;
use MongoDB\Collection;
use MongoDB\Database;
use MongoDB\InsertOneResult;
use ReflectionClass;
use ReflectionException;

class InsertXTest extends TestCase
{
    /**
     * @return void
     * @throws ReflectionException
     */
    public function test_function(): void
    {
        // prepare
        $this->expectException(DbOperationFailedException::class);
        $this->expectExceptionMessage('Insert failed');
        $reflection = new ReflectionClass(Mongo::class);
        $instance = $reflection->newInstanceWithoutConstructor();
        $this->setPropertyByReflection($instance, 'instance', $instance);
        $this->setPropertyByReflection($instance, 'data', ['collection' => ['id' => []]]);
        $insertOneResult = Mockery::mock(InsertOneResult::class);
        $insertOneResult->shouldReceive('getInsertedCount')->andReturn(0);
        $mockMongoCollection = Mockery::mock(Collection::class);
        $mockMongoCollection->shouldReceive('insertOne')->times(1)->with([], ['w' => 1])->andReturn($insertOneResult);
        $mockMongoDatabase = Mockery::mock(Database::class);
        $mockMongoDatabase->shouldReceive('selectCollection')->times(1)->with('collection')->andReturn($mockMongoCollection);
        $this->setPropertyByReflection($instance, 'database', $mockMongoDatabase);

        // invoke logic &  test
        $this->invokeMethodByReflection($instance, 'insert', ['collection', 'id']);
    }
}
