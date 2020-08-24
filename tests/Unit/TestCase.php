<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Tests\PrivateMethod;

abstract class TestCase extends BaseTestCase
{
    use PrivateMethod;
}
