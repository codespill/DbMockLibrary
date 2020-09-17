<?php declare(strict_types=1);

namespace DbMockLibrary\Test\MockDataManipulation;

use DbMockLibrary\Exceptions\AlreadyInitializedException;
use DbMockLibrary\MockDataManipulation;
use DbMockLibrary\Test\TestCase;
use UnexpectedValueException;

class DeleteRowXTest extends TestCase
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
        MockDataManipulation::initDataContainer(['collection' => ['id' => []]]);

        // invoke logic & test
        MockDataManipulation::getInstance()->deleteRow('fooBar', ['id']);
    }
}
