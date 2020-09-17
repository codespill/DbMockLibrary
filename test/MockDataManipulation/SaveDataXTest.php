<?php declare(strict_types=1);

namespace DbMockLibrary\Test\MockDataManipulation;

use DbMockLibrary\Exceptions\AlreadyInitializedException;
use DbMockLibrary\MockDataManipulation;
use DbMockLibrary\Test\TestCase;
use UnexpectedValueException;

class SaveDataXTest extends TestCase
{
    /**
     * @dataProvider getData
     *
     * @param array $data
     *
     * @return void
     * @throws AlreadyInitializedException
     */
    public function test_function(array $data): void
    {
        // prepare
        $this->expectException($data['exception']);
        $this->expectExceptionMessage($data['message']);
        MockDataManipulation::initDataContainer(['collection' => ['id' => ['field' => 'value']]]);

        // invoke logic & test
        MockDataManipulation::getInstance()->saveData($data['value'], $data['collection'], $data['id'], $data['field']);
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return [
            // #0 id param invalid
            [
                [
                    'value' => 'value',
                    'collection' => 'fooBar',
                    'id' => 'id',
                    'field' => 'field',
                    'strict' => true,
                    'exception' => UnexpectedValueException::class,
                    'message' => 'Non existing collection'
                ]
            ],
            // #1 field is required in a collection, but is missing
            [
                [
                    'value' => 'value',
                    'collection' => 'collection',
                    'id' => 'fooBar',
                    'field' => 'fooBar',
                    'strict' => true,
                    'exception' => UnexpectedValueException::class,
                    'message' => 'Non existing row'
                ]
            ],
            // #2 field is a row, so value should be array
            [
                [
                    'value' => 'value',
                    'collection' => 'collection',
                    'id' => 'id',
                    'field' => '',
                    'strict' => false,
                    'exception' => UnexpectedValueException::class,
                    'message' => 'Row should be an array of fields'
                ]
            ],
            // #3 field is a collection, so value should be at least 2-dimensional array (array of rows)
            [
                [
                    'value' => ['value'],
                    'collection' => 'collection',
                    'id' => '',
                    'field' => '',
                    'strict' => false,
                    'exception' => UnexpectedValueException::class,
                    'message' => 'Collection has to be array of rows which are all arrays of fields'
                ]
            ],
            // #4 field a database, so value should be at least 3-dimensional array (array of collections)
            [
                [
                    'value' => [['value']],
                    'collection' => '',
                    'id' => '',
                    'field' => '',
                    'strict' => false,
                    'exception' => UnexpectedValueException::class,
                    'message' => 'Data has to be an array of collections which are all arrays of rows which are all arrays of fields'
                ]
            ]
        ];
    }
}
