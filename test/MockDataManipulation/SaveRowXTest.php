<?php declare(strict_types=1);

namespace DbMockLibrary\Test\MockDataManipulation;

use DbMockLibrary\Exceptions\AlreadyInitializedException;
use DbMockLibrary\MockDataManipulation;
use DbMockLibrary\Test\TestCase;
use UnexpectedValueException;

class SaveRowXTest extends TestCase
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
        MockDataManipulation::getInstance()->saveRow($data['value'], $data['collection'], $data['id']);
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return [
            // #0 field is a row, so value should be array
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
            ]
        ];
    }
}
