<?php declare(strict_types=1);

namespace DbMockLibrary\Test\AbstractImplementation;

use DbMockLibrary\AbstractImplementation;
use DbMockLibrary\MockMethodCalls;

class FakeImplementation extends AbstractImplementation
{
    /**
     * Insert into database
     *
     * @param string $collection
     * @param $id
     *
     * @return void
     */
    protected function insert(string $collection, $id): void
    {
        MockMethodCalls::getInstance()->recordTrace();
    }

    /**
     * Delete from database
     *
     * @param string $collection
     * @param $id
     *
     * @return void
     */
    protected function delete(string $collection, $id): void
    {
        MockMethodCalls::getInstance()->recordTrace();
    }
}
