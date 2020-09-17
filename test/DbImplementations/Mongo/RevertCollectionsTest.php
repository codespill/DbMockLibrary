<?php declare(strict_types=1);

namespace DbMockLibrary\Test\DbImplementations\Mongo;

use DbMockLibrary\DbImplementations\Mongo;
use DbMockLibrary\Exceptions\AlreadyInitializedException;
use DbMockLibrary\Test\TestCase;
use MongoDB\Exception\InvalidArgumentException;
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
     * @throws InvalidArgumentException
     * @throws ReflectionException
     */
    public function test_function(array $data): void
    {
        // prepare
        Mongo::initMongo(['collection1' => ['id1' => [1], 'id2' => [2]], 'collection2' => ['id3' => [1], 'id4' => [2]]],
            'foo', []);
        $reflection = new ReflectionClass(Mongo::class);
        $dataProperty = $reflection->getProperty('data');
        $dataProperty->setAccessible(true);
        $dataProperty->setValue(Mongo::getInstance(), ['collection1' => [], 'collection2' => []]);

        // invoke logic
        Mongo::getInstance()->revertCollections($data['collections']);

        // test
        $this->assertEquals($data['expected'], $dataProperty->getValue(Mongo::getInstance()));
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
            ]
        ];
    }
}
