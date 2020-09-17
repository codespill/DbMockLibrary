<?php declare(strict_types=1);

namespace DbMockLibrary\Test\AbstractImplementation;

use ReflectionException;

class RecordInsertTest extends FakeTestCase
{
    /**
     * @dataProvider getData
     *
     * @param array $data
     *
     * @return void
     * @throws ReflectionException
     */
    public function test_function(array $data): void
    {
        // prepare
        $this->setPropertyByReflection($this->fake, 'insertedIntoDb', $data['insertedIntoDb']);

        // invoke logic
        $this->invokeMethodByReflection($this->fake, 'recordInsert', [$data['collection'], $data['id']]);

        // test
        $this->assertEquals($data['expected'], $this->getPropertyByReflection($this->fake, 'insertedIntoDb'));
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return [
            // #0 already recorded
            [
                [
                    'insertedIntoDb' => [['collection1' => 'id1']],
                    'collection' => 'collection1',
                    'id' => 'id1',
                    'expected' => [['collection1' => 'id1']],
                ],
            ],
            // #1 new row, same collection
            [
                [
                    'insertedIntoDb' => [['collection1' => 'id1']],
                    'collection' => 'collection1',
                    'id' => 'id2',
                    'expected' => [['collection1' => 'id1'], ['collection1' => 'id2']],
                ]
            ],
            // #0 new collection
            [
                [
                    'insertedIntoDb' => [['collection1' => 'id1']],
                    'collection' => 'collection2',
                    'id' => 'id2',
                    'expected' => [['collection1' => 'id1'], ['collection2' => 'id2']],
                ],
            ],
        ];
    }
}
