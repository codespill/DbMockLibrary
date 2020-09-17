<?php declare(strict_types=1);

namespace DbMockLibrary\Test\MockDataManipulation;

use DbMockLibrary\Exceptions\AlreadyInitializedException;
use DbMockLibrary\MockDataManipulation;
use DbMockLibrary\Test\TestCase;
use ReflectionClass;
use ReflectionException;

class SaveCollectionTest extends TestCase
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
        MockDataManipulation::getInstance()->saveCollection($data['value'], $data['collection']);

        // test
        $this->assertEquals($data['expected'], $dataProperty->getValue(MockDataManipulation::getInstance()));
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return [
            // #0 overwrite collection
            [
                [
                    'value' => ['fooBar' => ['fooBar' => 'fooBar']],
                    'collection' => 'collection',
                    'expected' => ['collection' => ['fooBar' => ['fooBar' => 'fooBar']]]
                ]
            ],
            // #1 add new collection
            [
                [
                    'value' => ['fooBar' => ['fooBar' => 'fooBar']],
                    'collection' => 'fooBar',
                    'expected' => [
                        'collection' => ['id' => ['field' => 'value']],
                        'fooBar' => ['fooBar' => ['fooBar' => 'fooBar']]
                    ]
                ]
            ]
        ];
    }
}
