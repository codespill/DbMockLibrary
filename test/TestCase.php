<?php declare(strict_types=1);

namespace DbMockLibrary\Test;

use DbMockLibrary\Base;
use DbMockLibrary\DataContainer;
use DbMockLibrary\DbImplementations\Mongo;
use DbMockLibrary\DbImplementations\MySQL;
use DbMockLibrary\DependencyHandler;
use DbMockLibrary\MockMethodCalls;
use InvalidArgumentException;
use Mockery;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use ReflectionClass;
use ReflectionException;

class TestCase extends PHPUnitTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        Mockery::close();

        $reflections = [
            new ReflectionClass(Base::class),
            new ReflectionClass(MockMethodCalls::class),
            new ReflectionClass(DataContainer::class),
            new ReflectionClass(DependencyHandler::class),
            new ReflectionClass(Mongo::class),
            new ReflectionClass(MySQL::class)
        ];

        foreach ($reflections as $reflection) {
            $staticProperties = $reflection->getStaticProperties();
            if (!is_null($staticProperties['instance'])) {
                // destroy has to be used because of the bug/feature with getStaticProperty method
                $getInstanceMethod = $reflection->getMethod('getInstance');
                $destroy = 'destroy';
                $getInstanceMethod->invoke($reflection)->$destroy();
            }
        }
    }

    /**
     * @param mixed $class
     * @param string $property
     * @param mixed $value
     *
     * @return void
     * @throws InvalidArgumentException
     * @throws ReflectionException
     */
    public function setPropertyByReflection($class, $property, $value): void
    {
        if (
            !is_object($class)
            && !(
                !is_string($class)
                || !class_exists($class)
            )
        ) {
            throw new InvalidArgumentException('Object argument is not an object: ' . var_export($class, true));
        }
        if (!is_string($property)) {
            throw new InvalidArgumentException('Property argument is not a string: ' . var_export($property, true));
        }

        $reflection = new ReflectionClass($class);
        $propertyReflection = $reflection->getProperty($property);
        $propertyReflection->setAccessible(true);
        $propertyReflection->setValue($class, $value);
    }

    /**
     * @param mixed $class
     * @param string $property
     *
     * @return mixed
     * @throws InvalidArgumentException
     * @throws ReflectionException
     */
    public function getPropertyByReflection($class, $property)
    {
        if (
            !is_object($class)
            && !(
                !is_string($class)
                || !class_exists($class)
            )
        ) {
            throw new InvalidArgumentException('Object argument is not an object: ' . var_export($class, true));
        }
        if (!is_string($property)) {
            throw new InvalidArgumentException('Property argument is not a string: ' . var_export($property, true));
        }

        $reflection = new ReflectionClass($class);
        $propertyReflection = $reflection->getProperty($property);
        $propertyReflection->setAccessible(true);

        return $propertyReflection->getValue($class);
    }

    /**
     * @param mixed $class
     * @param string $method
     * @param array $arguments
     *
     * @return mixed
     * @throws InvalidArgumentException
     * @throws ReflectionException
     */
    public function invokeMethodByReflection($class, $method, array $arguments)
    {
        if (
            !is_object($class)
            && !(
                !is_string($class)
                || !class_exists($class)
            )
        ) {
            throw new InvalidArgumentException('Object argument is not an object: ' . var_export($class, true));
        }
        if (!is_string($method)) {
            throw new InvalidArgumentException('Method argument is not a string: ' . var_export($method, true));
        }

        $reflection = new ReflectionClass($class);
        $methodReflection = $reflection->getMethod($method);
        $methodReflection->setAccessible(true);

        return $methodReflection->invokeArgs($class, $arguments);
    }
} 
