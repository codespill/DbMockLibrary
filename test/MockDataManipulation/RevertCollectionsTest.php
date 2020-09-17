<?php declare(strict_types=1);

namespace DbMockLibrary\Test\MockDataManipulation;

use DbMockLibrary\Exceptions\AlreadyInitializedException;
use DbMockLibrary\MockDataManipulation;
use DbMockLibrary\Test\TestCase;
use ReflectionClass;
use ReflectionException;

class RevertCollectionsTest extends TestCase
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
        MockDataManipulation::initDataContainer([
            'collection1' => ['id1' => [1], 'id2' => [2]],
            'collection2' => ['id3' => [1], 'id4' => [2]]
        ]);
        $reflection = new ReflectionClass(MockDataManipulation::class);
        $dataProperty = $reflection->getProperty('data');
        $dataProperty->setAccessible(true);
        if (!empty($data['newCollection'])) {
            $dataProperty->setValue(MockDataManipulation::getInstance(), [
                'collection1' => [],
                'collection2' => [],
                'newCollection' => $data['newCollection']
            ]);
        } else {
            $dataProperty->setValue(MockDataManipulation::getInstance(), ['collection1' => [], 'collection2' => []]);
        }

        // invoke logic
        MockDataManipulation::getInstance()->revertCollections($data['collections']);

        // test
        $this->assertEquals($data['expected'], $dataProperty->getValue(MockDataManipulation::getInstance()));
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return [
            // #0 revert selected collections
            [
                [
                    'collections' => ['collection2'],
                    'expected' => ['collection1' => [], 'collection2' => ['id3' => [1], 'id4' => [2]]]
                ]
            ],
            // #1 revert all collections
            [
                [
                    'collections' => [],
                    'expected' => [
                        'collection1' => ['id1' => [1], 'id2' => [2]],
                        'collection2' => ['id3' => [1], 'id4' => [2]]
                    ]
                ]
            ],
            // #2 collection that wasn't in initial data is dropped
            [
                [
                    'collections' => ['newCollection'],
                    'newCollection' => ['id1' => [1], 'id2' => [2]],
                    'expected' => ['collection1' => [], 'collection2' => []]
                ]
            ]
        ];
    }
}
