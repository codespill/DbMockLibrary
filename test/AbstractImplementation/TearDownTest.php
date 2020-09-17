<?php declare(strict_types=1);

namespace DbMockLibrary\Test\AbstractImplementation;

use DbMockLibrary\MockMethodCalls;
use ReflectionException;

class TearDownTest extends FakeTestCase
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
        $this->setPropertyByReflection($this->fake, 'data', $data['data']);

        // invoke logic
        $this->fake->tearDown($data['records']);

        // test
        foreach ($data['arguments'] as $key => $arguments) {
            $this->assertEquals(1, MockMethodCalls::getInstance()->wasCalledCount(
                FakeImplementation::class,
                'delete',
                $arguments
            ));
        }
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return [
            // #0 delete everything
            [
                [
                    'records' => [],
                    'data' => ['collection' => ['id' => []]],
                    'arguments' => [['collection', 'id']],
                ],
            ],
            // #1 delete entire collection
            [
                [
                    'records' => ['collection1'],
                    'data' => [
                        'collection1' => [
                            'id11' => ['column1' => 1],
                            'id12' => ['column1' => 2],
                        ],
                    ],
                    'arguments' => [
                        ['collection1', 'id11'],
                        ['collection1', 'id12']
                    ],
                ]
            ],
            // #2 delete row
            [
                [
                    'records' => ['collection3' => ['id3']],
                    'data' => [
                        'collection1' => [
                            'id11' => ['column1' => 1],
                            'id12' => ['column1' => 2],
                        ],
                        'collection2' => ['id2' => ['column2' => 1]],
                        'collection3' => ['id3' => ['column3' => 1]]
                    ],
                    'arguments' => [
                        ['collection3', 'id3']
                    ],
                ]
            ],
        ];
    }
}
