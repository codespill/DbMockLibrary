<?php declare(strict_types=1);

namespace DbMockLibrary;

use Exception;
use UnexpectedValueException;

class MockMethodCalls extends Base
{
    /**
     * @var array $callArguments
     */
    protected array $callArguments = [];

    /**
     * @var array $traces
     */
    protected array $traces = [];

    /**
     * @param $class
     * @param string $method
     * @param array|null $arguments
     *
     * @return int
     */
    public function wasCalledCount($class, string $method, array $arguments = null): int
    {
        $traces = $this->getFullTraceDetails($class, $method);
        $counter = 0;

        foreach ($traces as $trace) {
            foreach ($trace as $calls) {
                if ($calls['function'] == $method && $calls['class'] == $class && (is_null($arguments) ||
                        (isset($calls['args']) && $calls['args'] == $arguments))) {
                    $counter++;
                }
            }
        }

        return $counter;
    }

    /**
     * @param $object
     * @param string $method
     * @param array $arguments
     *
     * @return void
     */
    public function recordArguments($object, string $method, array $arguments): void
    {
        if (!method_exists($object, $method)) {
            throw new UnexpectedValueException('Invalid method');
        }

        $this->callArguments[] = is_string($object) ? $object : [get_class($object) . '::' . $method => $arguments];
    }

    /**
     * @return void
     */
    public function recordTrace(): void
    {
        try {
            throw new Exception();
        } catch (Exception $e) {
            $tmp = $e->getTrace();
            array_shift($tmp);
            $this->traces[] = $tmp;
        }
    }

    /**
     * @param $class
     * @param string $method
     *
     * @return array
     */
    protected function getFullTraceDetails($class, string $method): array
    {
        if (!method_exists($class, $method)) {
            throw new UnexpectedValueException('Invalid method');
        }

        $return = [];
        // iterate through recorded traces
        foreach ($this->traces as $trace) {
            $save = false;
            $usefulData = [];
            // iterate through calls withing every trace
            foreach ($trace as $calls) {
                // if call is made inside the trace, save the trace and return it
                if ($calls['function'] == $method && $calls['class'] == $class) {
                    $save = true;
                }
                // prepare trace as if it was going to be returned...
                $usefulData[] = [
                    'function' => $calls['function'],
                    'class' => $calls['class'],
                    'args' => $calls['args'],
                ];
            }
            // ... but only return it if needed
            if ($save) {
                $return[] = $usefulData;
            }
        }

        return $return;
    }

    /**
     * @return array
     */
    public function getCallArguments()
    {
        return $this->callArguments;
    }

    /**
     * @return void
     */
    public function reset()
    {
        $this->callArguments = [];
        $this->traces = [];
    }
}
