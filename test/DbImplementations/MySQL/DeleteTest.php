<?php declare(strict_types=1);

namespace DbMockLibrary\Test\DbImplementations\MySQL;

use DbMockLibrary\DbImplementations\MySQL;
use DbMockLibrary\Exceptions\AlreadyInitializedException;
use DbMockLibrary\Exceptions\InvalidDependencyException;
use DbMockLibrary\Test\TestCase;
use PDO;
use ReflectionClass;
use ReflectionException;

class DeleteTest extends TestCase
{
    protected PDO $pdo;

    /**
     * @return void
     * @throws AlreadyInitializedException
     * @throws InvalidDependencyException
     */
    protected function setUp(): void
    {
        $this->pdo ??= new PDO('mysql:host=127.0.0.1;', 'root', '');

        $stmt = $this->pdo->prepare('DROP DATABASE IF EXISTS `DbMockLibraryTest`');
        $stmt->execute();

        $stmt = $this->pdo->prepare('CREATE DATABASE `DbMockLibraryTest`');
        $stmt->execute();

        $stmt = $this->pdo->prepare('CREATE TABLE IF NOT EXISTS DbMockLibraryTest.testTable (`id` INT, `foo` INT, PRIMARY KEY (`id`, `foo`))');
        $stmt->execute();

        $stmt = $this->pdo->prepare('INSERT INTO DbMockLibraryTest.testTable (`id`, `foo`) VALUES (0, 0)');
        $stmt->execute();

        MySQL::initMySQL(['testTable' => [1 => ['foo' => 0, 'id' => 0]]], '127.0.0.1', 'DbMockLibraryTest', 'root', '',
            []);
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM DbMockLibraryTest.testTable WHERE `id` = 0');
        $stmt->execute();

        $stmt = $this->pdo->prepare('DROP DATABASE IF EXISTS `DbMockLibraryTest`');
        $stmt->execute();

        if (MySQL::getInstance()) {
            MySQL::getInstance()->destroy();
        }
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function test_function(): void
    {
        // prepare
        $stmt = $this->pdo->prepare('SELECT * FROM `DbMockLibraryTest`.testTable WHERE `id` = 0');
        $stmt->execute();
        $result = $stmt->fetchAll();
        $reflection = new ReflectionClass(MySQL::getInstance());
        $deleteMethod = $reflection->getMethod('delete');
        $deleteMethod->setAccessible(true);

        // test
        $this->assertCount(1, $result);

        // invoke logic
        $deleteMethod->invoke(MySQL::getInstance(), 'testTable', 1);

        // prepare
        $stmt->execute();
        $result = $stmt->fetchAll();

        // test
        $this->assertCount(0, $result);
    }
}
