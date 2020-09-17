<?php declare(strict_types=1);

namespace DbMockLibrary\Test\DbImplementations\MySQL;

use DbMockLibrary\DbImplementations\MySQL;
use DbMockLibrary\Exceptions\AlreadyInitializedException;
use DbMockLibrary\Exceptions\InvalidDependencyException;
use DbMockLibrary\Test\TestCase;
use PDO;
use ReflectionException;
use UnexpectedValueException;

class InitMySQLXTest extends TestCase
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
     * @throws ReflectionException
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        $stmt = $this->pdo->prepare('DROP DATABASE IF EXISTS `DbMockLibraryTest`');
        $stmt->execute();
    }

    /**
     * @dataProvider getData
     *
     * @param array $data
     *
     * @return void
     * @throws AlreadyInitializedException
     * @throws InvalidDependencyException
     */
    public function test_function(array $data): void
    {
        // prepare
        $this->expectException($data['exception']);
        $this->expectExceptionMessage($data['errorMessage']);

        // invoke logic
        MySQL::initMySQL($data['initialData'], $data['serverName'], $data['database'], $data['username'],
            $data['password'], []);
        if (isset($data['initTwice'])) {
            MySQL::initMySQL($data['initialData'], $data['serverName'], $data['database'], $data['username'],
                $data['password'], []);
        }
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return [
            // #0 instance already initialized
            [
                [
                    'exception' => AlreadyInitializedException::class,
                    'errorMessage' => 'MySQL library already initialized',
                    'serverName' => '127.0.0.1',
                    'database' => 'DbMockLibraryTest',
                    'username' => 'root',
                    'password' => '',
                    'initialData' => [],
                    'initTwice' => true
                ]
            ],
            // #1 invalid server name parameter
            [
                [
                    'exception' => UnexpectedValueException::class,
                    'errorMessage' => 'Invalid server name',
                    'serverName' => '',
                    'database' => 'DbMockLibraryTest',
                    'username' => 'root',
                    'password' => '',
                    'initialData' => []
                ]
            ],
            // #2 invalid database parameter
            [
                [
                    'exception' => UnexpectedValueException::class,
                    'errorMessage' => 'Invalid database name',
                    'serverName' => '127.0.0.1',
                    'database' => '',
                    'username' => 'root',
                    'password' => '',
                    'initialData' => []
                ]
            ],
            // #3 invalid username parameter
            [
                [
                    'exception' => UnexpectedValueException::class,
                    'errorMessage' => 'Invalid username',
                    'serverName' => '127.0.0.1',
                    'database' => 'DbMockLibraryTest',
                    'username' => '',
                    'password' => '',
                    'initialData' => []

                ]
            ],
            // #4 invalid table names (not a string) in initial data parameter
            [
                [
                    'exception' => UnexpectedValueException::class,
                    'errorMessage' => 'Invalid table names',
                    'serverName' => '127.0.0.1',
                    'database' => 'DbMockLibraryTest',
                    'username' => 'root',
                    'password' => '',
                    'initialData' => [1 => ['foo' => 'value', 'id' => 1]]
                ]
            ],
            // #5 missing (part or whole) primary key in initial data
            [
                [
                    'exception' => UnexpectedValueException::class,
                    'errorMessage' => 'Missing keys in initial data for table: testTable',
                    'serverName' => '127.0.0.1',
                    'database' => 'DbMockLibraryTest',
                    'username' => 'root',
                    'password' => '',
                    'initialData' => ['testTable' => [1 => ['foo' => 1]]]
                ]
            ]
        ];
    }
}
