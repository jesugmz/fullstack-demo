<?php declare(strict_types = 1);

namespace Joking\Tests\Functional;

use PHPUnit\Framework\TestCase;

class SystemTest extends TestCase
{
    public function testEnvVariablesExistence()
    {
        // The idea is to ensure required env vars exists
        $this->assertNotEmpty(getenv('APP_ENV'));
        $this->assertNotEmpty(getenv('APP_DEBUG'));
        $this->assertNotEmpty(getenv('KERNEL_CLASS'));
    }
}
