<?php declare(strict_types=1);

namespace DbMockLibrary\Test\MockDataManipulation;

use DbMockLibrary\Exceptions\AlreadyInitializedException;
use DbMockLibrary\MockDataManipulation;
use DbMockLibrary\Test\TestCase;
use ReflectionClass;
use ReflectionException;

class TruncateCollectionsTest extends TestCase
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

        // invoke logic
        MockDataManipulation::getInstance()->truncateCollections($data['collections']);

        // test
        $this->assertEquals($data['expected'], $dataProperty->getValue(MockDataManipulation::getInstance()));
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return [
            // #0 truncate selected collections
            [
                [
                    'collections' => ['collection1'],
                    'expected' => ['collection1' => [], 'collection2' => ['id3' => [1], 'id4' => [2]]]
                ]
            ],
            // #1 truncate all collections
            [
                [
                    'collections' => [],
                    'expected' => ['collection1' => [], 'collection2' => []]
                ]
            ]
        ];
    }
}
