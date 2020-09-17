<?php declare(strict_types=1);

namespace DbMockLibrary\Test\DataContainer;

use DbMockLibrary\DataContainer;
use DbMockLibrary\Exceptions\AlreadyInitializedException;
use DbMockLibrary\Test\TestCase;
use InvalidArgumentException;
use ReflectionException;
use stdClass;
use UnexpectedValueException;

class ValidateIdsXTest extends TestCase
{
    /**
     * @param array $data
     *
     * @dataProvider getData
     *
     * @return void
     * @throws AlreadyInitializedException
     * @throws ReflectionException
     */
    public function test_function(array $data): void
    {
        // prepare
        $this->expectException($data['exception']);
        $this->expectExceptionMessage($data['errorMessage']);
        DataContainer::initDataContainer(['collection' => ['id' => []]]);

        // invoke logic & test
        $this->invokeMethodByReflection(DataContainer::getInstance(), 'validateIds',
            [$data['collection'], $data['id']]);
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return [
            // #0 invalid collection
            [
                [
                    'id' => ['id'],
                    'collection' => 'fooBar',
                    'exception' => UnexpectedValueException::class,
                    'errorMessage' => 'Collection \'fooBar\' does not exist'
                ]
            ],
            // #1 invalid id
            [
                [
                    'id' => ['fooBar'],
                    'collection' => 'collection',
                    'exception' => UnexpectedValueException::class,
                    'errorMessage' => 'Element with id \'fooBar\' does not exist'
                ]
            ],
            // #2 invalid id type
            [
                [
                    'id' => [new stdClass()],
                    'collection' => 'collection',
                    'exception' => InvalidArgumentException::class,
                    'errorMessage' => 'Invalid id ' . var_export(new stdClass(), true)
                ]
            ]
        ];
    }
}
