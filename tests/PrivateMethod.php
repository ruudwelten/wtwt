<?php

namespace Tests;

trait PrivateMethod
{
    /**
     * Gets a private method for testing.
     */
    public function getPrivateMethod($class, $methodName)
    {
        $class = new \ReflectionClass($class);
        $method = $class->getMethod($methodName);
        $method->setAccessible(true);

        return $method;
    }
}
