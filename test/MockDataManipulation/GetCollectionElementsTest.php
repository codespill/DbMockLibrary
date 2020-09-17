<?php declare(strict_types=1);

namespace DbMockLibrary\Test\MockDataManipulation;

use DbMockLibrary\Exceptions\AlreadyInitializedException;
use DbMockLibrary\MockDataManipulation;
use DbMockLibrary\Test\TestCase;

class GetCollectionElementsTest extends TestCase
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
        MockDataManipulation::initDataContainer(['collection' => ['id' => [1]]]);

        // invoke logic
        $result = MockDataManipulation::getInstance()->getCollectionElements($data['collection'], $data['id']);

        // test
        $this->assertEquals($data['expected'], $result);
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return [
            // #0 collection element found
            [
                [
                    'id' => 'id',
                    'collection' => 'collection',
                    'expected' => [1]
                ]
            ],
            // #1 collection found
            [
                [
                    'id' => null,
                    'collection' => 'collection',
                    'expected' => ['id' => [1]]
                ]
            ]
        ];
    }
}
