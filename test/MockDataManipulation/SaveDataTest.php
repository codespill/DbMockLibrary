<?php declare(strict_types=1);

namespace DbMockLibrary\Test\MockDataManipulation;

use DbMockLibrary\Exceptions\AlreadyInitializedException;
use DbMockLibrary\MockDataManipulation;
use DbMockLibrary\Test\TestCase;
use ReflectionClass;
use ReflectionException;

class SaveDataTest extends TestCase
{
    /**
     * @dataProvider getData
     *
     * @param array $data
     *
     * @return void
     * @throws AlreadyInitializedException
     * @throws ReflectionException
     */
    public function test_function(array $data): void
    {
        // prepare
        MockDataManipulation::initDataContainer(['collection' => ['id' => ['field' => 'value']]]);
        $reflection = new ReflectionClass(MockDataManipulation::class);
        $dataProperty = $reflection->getProperty('data');
        $dataProperty->setAccessible(true);

        // invoke logic
        MockDataManipulation::getInstance()->saveData($data['value'], $data['collection'], $data['id'], $data['field']);

        // test
        $this->assertEquals($data['expected'], $dataProperty->getValue(MockDataManipulation::getInstance()));
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return [
            // #0 overwrite database
            [
                [
                    'value' => ['fooBar' => ['fooBar' => ['fooBar' => 'fooBar']]],
                    'collection' => '',
                    'id' => '',
                    'field' => '',
                    'expected' => ['fooBar' => ['fooBar' => ['fooBar' => 'fooBar']]]
                ]
            ],
            // #1 overwrite collection
            [
                [
                    'value' => ['fooBar' => ['fooBar' => 'fooBar']],
                    'collection' => 'collection',
                    'id' => '',
                    'field' => '',
                    'expected' => ['collection' => ['fooBar' => ['fooBar' => 'fooBar']]]
                ]
            ],
            // #2 add new collection
            [
                [
                    'value' => ['fooBar' => ['fooBar' => 'fooBar']],
                    'collection' => 'fooBar',
                    'id' => '',
                    'field' => '',
                    'expected' => [
                        'collection' => ['id' => ['field' => 'value']],
                        'fooBar' => ['fooBar' => ['fooBar' => 'fooBar']]
                    ]
                ]
            ],
            // #3 overwrite row
            [
                [
                    'value' => ['fooBar' => 'fooBar'],
                    'collection' => 'collection',
                    'id' => 'id',
                    'field' => '',
                    'expected' => ['collection' => ['id' => ['fooBar' => 'fooBar']]]
                ]
            ],
            // #4 add new row
            [
                [
                    'value' => ['fooBar' => 'fooBar'],
                    'collection' => 'collection',
                    'id' => 'fooBar',
                    'field' => '',
                    'expected' => ['collection' => ['id' => ['field' => 'value'], 'fooBar' => ['fooBar' => 'fooBar']]]
                ]
            ],
            // #5 overwrite field
            [
                [
                    'value' => 'fooBar',
                    'collection' => 'collection',
                    'id' => 'id',
                    'field' => 'field',
                    'expected' => ['collection' => ['id' => ['field' => 'fooBar']]]
                ]
            ],
            // #6 add new field
            [
                [
                    'value' => 'fooBar',
                    'collection' => 'collection',
                    'id' => 'id',
                    'field' => 'fooBar',
                    'expected' => ['collection' => ['id' => ['field' => 'value', 'fooBar' => 'fooBar']]]
                ]
            ]
        ];
    }
}
