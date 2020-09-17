<?php declare(strict_types=1);

namespace DbMockLibrary\Test\MockDataManipulation;

use DbMockLibrary\Exceptions\AlreadyInitializedException;
use DbMockLibrary\MockDataManipulation;
use DbMockLibrary\Test\TestCase;
use UnexpectedValueException;

class GetCollectionElementsXTest extends TestCase
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
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage($data['errorMessage']);
        MockDataManipulation::initDataContainer(['collection' => ['id' => []]]);

        // invoke logic & test
        MockDataManipulation::getInstance()->getCollectionElements($data['collection'], $data['id']);
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return [
            // #0 invalid collection, id present, validateCollections, inside validateIds, throws the exception
            [
                [
                    'id' => 'id',
                    'collection' => 'fooBar',
                    'errorMessage' => 'Collection \'fooBar\' does not exist'
                ]
            ],
            // #1 invalid id, id present, validateIds throws the exception
            [
                [
                    'id' => 'fooBar',
                    'collection' => 'collection',
                    'errorMessage' => 'Element with id \'fooBar\' does not exist'
                ]
            ],
            // #2 invalid collection, id not present, validateCollections throws the exception
            [
                [
                    'id' => null,
                    'collection' => 'fooBar',
                    'errorMessage' => 'Collection \'fooBar\' does not exist'
                ]
            ]
        ];
    }
}
