<?php declare(strict_types=1);

namespace DbMockLibrary\Test\MockDataManipulation;

use DbMockLibrary\Exceptions\AlreadyInitializedException;
use DbMockLibrary\MockDataManipulation;
use DbMockLibrary\Test\TestCase;
use UnexpectedValueException;

class GetAllIdsXTest extends TestCase
{
    /**
     * @return void
     * @throws AlreadyInitializedException
     */
    public function test_function(): void
    {
        // prepare
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Collection \'fooBar\' does not exist');
        MockDataManipulation::initDataContainer(['collection' => []]);

        // invoke logic & test
        MockDataManipulation::getInstance()->getAllIds(['fooBar']);
    }
}
