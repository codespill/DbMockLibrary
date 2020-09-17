<?php declare(strict_types=1);

namespace DbMockLibrary\Test\MockDataManipulation;

use DbMockLibrary\Exceptions\AlreadyInitializedException;
use DbMockLibrary\MockDataManipulation;
use DbMockLibrary\Test\TestCase;
use ReflectionClass;
use ReflectionException;

class SaveFieldTest extends TestCase
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
        MockDataManipulation::getInstance()->saveField($data['value'], $data['collection'], $data['id'],
            $data['field']);

        // test
        $this->assertEquals($data['expected'], $dataProperty->getValue(MockDataManipulation::getInstance()));
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return [
            // #0 overwrite field
            [
                [
                    'value' => 'fooBar',
                    'collection' => 'collection',
                    'id' => 'id',
                    'field' => 'field',
                    'strict' => false,
                    'expected' => ['collection' => ['id' => ['field' => 'fooBar']]]
                ]
            ],
            // #1 add new field
            [
                [
                    'value' => 'fooBar',
                    'collection' => 'collection',
                    'id' => 'id',
                    'field' => 'fooBar',
                    'strict' => false,
                    'expected' => ['collection' => ['id' => ['field' => 'value', 'fooBar' => 'fooBar']]]
                ]
            ]
        ];
    }
}
