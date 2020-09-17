<?php declare(strict_types=1);

namespace DbMockLibrary\Test\DbImplementations\MySQL;

use DbMockLibrary\DbImplementations\MySQL;
use DbMockLibrary\Exceptions\DbOperationFailedException;
use DbMockLibrary\Test\TestCase;
use Mockery;
use PDO;
use PDOStatement;
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
        $reflection = new ReflectionClass(MySQL::class);
        $instance = $reflection->newInstanceWithoutConstructor();
        $this->setPropertyByReflection($instance, 'instance', $instance);
        $this->setPropertyByReflection($instance, 'primaryKeys', ['collection' => []]);
        $mockPDOStatement = Mockery::mock(PDOStatement::class);
        $mockPDOStatement->shouldReceive('execute')->times(1)->with([])->andReturn(false);
        $mockConnection = Mockery::mock(PDO::class);
        $mockConnection->shouldReceive('prepare')->times(1)->with('DELETE FROM collection WHERE ')->andReturn($mockPDOStatement);
        $this->setPropertyByReflection($instance, 'connection', $mockConnection);

        // invoke logic &  test
        $this->invokeMethodByReflection($instance, 'delete', ['collection', 0]);
    }
}
