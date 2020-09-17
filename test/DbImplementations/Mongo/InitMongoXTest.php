<?php declare(strict_types=1);

namespace DbMockLibrary\Test\DbImplementations\Mongo;

use DbMockLibrary\DbImplementations\Mongo;
use DbMockLibrary\Exceptions\AlreadyInitializedException;
use DbMockLibrary\Test\TestCase;
use MongoDB\Exception\InvalidArgumentException;
use UnexpectedValueException;

class InitMongoXTest extends TestCase
{
    /**
     * @return void
     */
    protected function tearDown(): void
    {
        if (Mongo::getInstance()) {
            Mongo::getInstance()->destroy();
        }
    }

    /**
     * @dataProvider getData
     *
     * @param array $data
     *
     * @return void
     * @throws AlreadyInitializedException
     * @throws InvalidArgumentException
     */
    public function test_function(array $data): void
    {
        // prepare
        $this->expectException($data['exception']);
        $this->expectExceptionMessage($data['errorMessage']);

        // invoke logic
        Mongo::initMongo($data['initialData'], $data['database'], []);
        if (isset($data['initTwice'])) {
            Mongo::initMongo($data['initialData'], $data['database'], []);
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
                    'errorMessage' => 'Mongo library already initialized',
                    'database' => 'DbMockLibraryTest',
                    'initialData' => [],
                    'initTwice' => true
                ]
            ],
            // #1 invalid database parameter
            [
                [
                    'exception' => UnexpectedValueException::class,
                    'errorMessage' => 'Invalid database name',
                    'database' => '',
                    'initialData' => []
                ]
            ],
            // #2 invalid collection names (not a string) in initial data parameter
            [
                [
                    'exception' => UnexpectedValueException::class,
                    'errorMessage' => 'Invalid collection names',
                    'database' => 'DbMockLibraryTest',
                    'initialData' => [1 => ['foo' => 'value', 'id' => 1]]
                ]
            ]
        ];
    }
}
