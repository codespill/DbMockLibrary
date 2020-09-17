<?php declare(strict_types=1);

namespace DbMockLibrary\Test\DbImplementations\MySQL;

use DbMockLibrary\DbImplementations\MySQL;
use DbMockLibrary\Exceptions\AlreadyInitializedException;
use DbMockLibrary\Exceptions\InvalidDependencyException;
use DbMockLibrary\Test\TestCase;
use PDO;
use ReflectionClass;
use ReflectionException;

class InitMySQLTest extends TestCase
{
    protected PDO $pdo;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->pdo ??= new PDO('mysql:host=127.0.0.1;', 'root', '');

        $stmt = $this->pdo->prepare('DROP DATABASE IF EXISTS `DbMockLibraryTest`');
        $stmt->execute();

        $stmt = $this->pdo->prepare('CREATE DATABASE `DbMockLibraryTest`');
        $stmt->execute();

        $stmt = $this->pdo->prepare('CREATE TABLE IF NOT EXISTS DbMockLibraryTest.testTable (`id` INT, `foo` INT, PRIMARY KEY (`id`))');
        $stmt->execute();
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        $stmt = $this->pdo->prepare('DROP DATABASE IF EXISTS `DbMockLibraryTest`');
        $stmt->execute();

        MySQL::getInstance()->destroy();
    }

    /**
     * @return void
     * @throws AlreadyInitializedException
     * @throws InvalidDependencyException
     * @throws ReflectionException
     */
    public function test_function(): void
    {
        // prepare
        $dataArray = ['testTable' => [1 => ['foo' => 1, 'id' => 1]]];

        // invoke logic
        MySQL::initMySQL($dataArray, '127.0.0.1', 'DbMockLibraryTest', 'root', '', []);

        // prepare
        $reflection = new ReflectionClass(MySQL::class);
        $staticProperties = $reflection->getStaticProperties();

        // test
        $this->assertInstanceOf(MySQL::class, $staticProperties['instance']);
        $this->assertEquals($dataArray, $staticProperties['initialData']);

        // prepare
        $dataProperty = $reflection->getProperty('data');
        $dataProperty->setAccessible(true);
        $primaryKeysProperty = $reflection->getProperty('primaryKeys');
        $primaryKeysProperty->setAccessible(true);

        // test
        $this->assertEquals($dataArray, $dataProperty->getValue($staticProperties['instance']));
        $this->assertEquals(['testTable' => ['id']], $primaryKeysProperty->getValue($staticProperties['instance']));
    }
}
