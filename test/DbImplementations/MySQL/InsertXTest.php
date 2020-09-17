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
        $reflection = new ReflectionClass(MySQL::class);
        $instance = $reflection->newInstanceWithoutConstructor();
        $this->setPropertyByReflection($instance, 'instance', $instance);
        $this->setPropertyByReflection($instance, 'data', ['collection' => [[]]]);
        $mockPDOStatement = Mockery::mock(PDOStatement::class);
        $mockPDOStatement->shouldReceive('execute')->times(1)->with([])->andReturn(false);
        $mockConnection = Mockery::mock(PDO::class);
        $mockConnection->shouldReceive('prepare')->times(1)->with('INSERT INTO collection () VALUES ();')->andReturn($mockPDOStatement);
        $this->setPropertyByReflection($instance, 'connection', $mockConnection);

        // invoke logic &  test
        $this->invokeMethodByReflection($instance, 'insert', ['collection', 0]);
    }
}
