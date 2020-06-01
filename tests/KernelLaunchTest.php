<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class KernelLaunchTest extends TestCase
{
    public function testCanBeInstanciedInProd(): void
    {
        $this->assertInstanceOf(App\Kernel::class, $kernel = new App\Kernel('prod', false) );
    }

    public function testCanBeInstanciedInDev(): void
    {
        $this->assertInstanceOf(App\Kernel::class, $kernel = new App\Kernel('dev', true) );
    }
}
