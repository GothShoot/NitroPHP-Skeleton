<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class KernelLaunchTest extends TestCase
{
    public function testCanBeCreatedFromValidEmailAddress(): void
    {
        $this->assertInstanceOf(App\Kernel::class, $kernel = new App\Kernel);
    }
}
