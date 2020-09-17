<?php declare(strict_types=1);

namespace DbMockLibrary\Test\MockDataManipulation;

use DbMockLibrary\Exceptions\AlreadyInitializedException;
use DbMockLibrary\MockDataManipulation;
use DbMockLibrary\Test\TestCase;

class GetAllIdsTest extends TestCase
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
        MockDataManipulation::initDataContainer([
            'collection1' => ['id1' => [1], 'id2' => [2]],
            'collection2' => ['id3' => [1], 'id4' => [2]]
        ]);

        // invoke logic
        $result = MockDataManipulation::getInstance()->getAllIds($data['collections'], $data['byCollection']);

        // test
        $this->assertEquals($data['expected'], $result);
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return [
            // #0 get all ids of the collections
            [
                [
                    'byCollection' => false,
                    'collections' => ['collection1'],
                    'expected' => ['id1', 'id2']
                ]
            ],
            // #1 get all ids of all collections
            [
                [
                    'byCollection' => false,
                    'collections' => [],
                    'expected' => ['id1', 'id2', 'id3', 'id4']
                ]
            ],
            // #2 get all ids of the collections, sort by collection
            [
                [
                    'byCollection' => true,
                    'collections' => ['collection1'],
                    'expected' => ['collection1' => ['id1', 'id2']]
                ]
            ]
        ];
    }
}
