<?php declare(strict_types=1);

namespace DbMockLibrary\Test\MockDataManipulation;

use DbMockLibrary\Exceptions\AlreadyInitializedException;
use DbMockLibrary\MockDataManipulation;
use DbMockLibrary\Test\TestCase;
use ReflectionClass;
use ReflectionException;

class GetAllCollectionsIfEmptyTest extends TestCase
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
        MockDataManipulation::initDataContainer(['collection1' => [], 'collection2' => []]);
        $reflection = new ReflectionClass(MockDataManipulation::class);
        $getAllCollectionsIfEmptyMethod = $reflection->getMethod('getAllCollectionsIfEmpty');
        $getAllCollectionsIfEmptyMethod->setAccessible(true);

        // invoke logic
        $result = $getAllCollectionsIfEmptyMethod->invoke(MockDataManipulation::getInstance(), $data['collections']);

        // test
        $this->assertEquals($data['expected'], $result);
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return [
            // #0 not empty
            [
                [
                    'collections' => ['collection1'],
                    'expected' => ['collection1']
                ]
            ],
            // #1 empty
            [
                [
                    'collections' => [],
                    'expected' => ['collection1', 'collection2']
                ]
            ]
        ];
    }
}
