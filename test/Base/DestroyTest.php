<?php declare(strict_types=1);

namespace DbMockLibrary\Test\Base;

use DbMockLibrary\Base;
use DbMockLibrary\Exceptions\AlreadyInitializedException;
use DbMockLibrary\Test\TestCase;
use ReflectionClass;

class DestroyTest extends TestCase
{
    /**
     * @return void
     * @throws AlreadyInitializedException
     */
    public function test_function(): void
    {
        // prepare
        Base::init();
        $reflection = new ReflectionClass(Base::class);
        $staticProperties = $reflection->getStaticProperties();

        // test
        $this->assertInstanceOf(Base::class, $staticProperties['instance']);

        // invoke logic
        Base::getInstance()->destroy();

        // prepare
        $staticProperties = $reflection->getStaticProperties();

        // test
        $this->assertNull($staticProperties['instance']);
    }
}
