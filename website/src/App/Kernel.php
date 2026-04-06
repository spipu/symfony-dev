<?php

declare(strict_types=1);

namespace App;

use App\DependencyInjection\AppExtension;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    private ?string $projectDir = null;

    public function getProjectDir(): string
    {
        if (null === $this->projectDir) {
            $this->projectDir = dirname(__DIR__, 2);
        }

        return $this->projectDir;
    }

    protected function build(ContainerBuilder $container): void
    {
        $container->registerExtension(new AppExtension());
    }
}
