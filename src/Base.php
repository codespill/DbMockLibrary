<?php declare(strict_types=1);

namespace DbMockLibrary;

use DbMockLibrary\Exceptions\AlreadyInitializedException;
use UnexpectedValueException;

class Base
{
    /**
     * @var ?object $instance
     */
    protected static ?object $instance = null;

    /**
     * Base constructor.
     */
    protected function __construct()
    {
    }

    /**
     * @return void
     *
     * @throws AlreadyInitializedException
     */
    public static function init(): void
    {
        if (!static::$instance) {
            static::$instance = new static();
        } else {
            throw new AlreadyInitializedException(get_class(static::$instance) . ' has already been initialized');
        }
    }

    /**
     * @return void
     */
    public static function destroy(): void
    {
        static::$instance = null;
    }

    /**
     * @return ?object
     */
    public static function getInstance(): ?object
    {
        if (empty(static::$instance)) {
            throw new UnexpectedValueException('Not initialized');
        }

        return static::$instance;
    }
}
