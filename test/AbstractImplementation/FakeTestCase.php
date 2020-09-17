<?php declare(strict_types=1);

namespace DbMockLibrary\Test\AbstractImplementation;

use DbMockLibrary\MockMethodCalls;
use DbMockLibrary\Test\TestCase;
use ReflectionClass;
use ReflectionException;

class FakeTestCase extends TestCase
{
    /**
     * @var FakeImplementation $fake
     */
    protected $fake;

    /**
     * @var MockMethodCalls $mmc
     */
    protected $mmc;

    /**
     * @throws ReflectionException
     */
    protected function setUp(): void
    {
        parent::setUp();

        $reflection = new ReflectionClass(FakeImplementation::class);
        $this->fake = $reflection->newInstanceWithoutConstructor();
        $this->setPropertyByReflection($this->fake, 'instance', $this->fake);

        $reflection = new ReflectionClass(MockMethodCalls::class);
        $this->mmc = $reflection->newInstanceWithoutConstructor();
        $this->setPropertyByReflection($this->mmc, 'instance', $this->mmc);
    }

    /**
     * @throws ReflectionException
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        $this->mmc->reset();
    }
}
