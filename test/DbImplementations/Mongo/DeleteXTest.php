<?php declare(strict_types=1);

namespace DbMockLibrary\Test\DbImplementations\Mongo;

use DbMockLibrary\DbImplementations\Mongo;
use DbMockLibrary\Exceptions\DbOperationFailedException;
use DbMockLibrary\Test\TestCase;
use Mockery;
use MongoDB\Collection;
use MongoDB\Database;
use MongoDB\DeleteResult;
use ReflectionClass;
use ReflectionException;

class DeleteXTest extends TestCase
{
    /**
     * @return void
     * @throws ReflectionException
     */
    public function test_function(): void
    {
        // prepare
        $this->expectException(DbOperationFailedException::class);
        $this->expectExceptionMessage('Delete failed');
        $reflection = new ReflectionClass(Mongo::class);
        $instance = $reflection->newInstanceWithoutConstructor();
        $this->setPropertyByReflection($instance, 'instance', $instance);
        $this->setPropertyByReflection($instance, 'data', ['collection' => ['id' => ['_id' => 1]]]);
        $deleteResult = Mockery::mock(DeleteResult::class);
        $deleteResult->shouldReceive('getDeletedCount')->times(1)->andReturn(0);
        $mockMongoCollection = Mockery::mock(Collection::class);
        $mockMongoCollection->shouldReceive('deleteOne')->times(1)->with(['_id' => 1],
            ['w' => 1])->andReturn($deleteResult);
        $mockMongoDatabase = Mockery::mock(Database::class);
        $mockMongoDatabase->shouldReceive('selectCollection')->times(1)->with('collection')->andReturn($mockMongoCollection);
        $this->setPropertyByReflection($instance, 'database', $mockMongoDatabase);

        // invoke logic &  test
        $this->invokeMethodByReflection($instance, 'delete', ['collection', 'id']);
    }
}
