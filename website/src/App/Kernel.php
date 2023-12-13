<?php

namespace App;

use App\DependencyInjection\AppExtension;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
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

    /**
     * Overridden because of APP extension to register
     * @param ContainerConfigurator $container
     * @param LoaderInterface $loader
     * @param ContainerBuilder $builder
     * @return void
     * @SuppressWarnings(PMD.ElseExpression)
     * @SuppressWarnings(PMD.UnusedFormalParameter)
     */
    protected function configureContainer(
        ContainerConfigurator $container,
        LoaderInterface $loader,
        ContainerBuilder $builder
    ): void {
        $builder->registerExtension(new AppExtension());

        $configDir = $this->getConfigDir();

        $container->import($configDir . '/{packages}/*.yaml');
        $container->import($configDir . '/{packages}/' . $this->environment . '/*.yaml');

        if (is_file($configDir . '/services.yaml')) {
            $container->import($configDir . '/services.yaml');
            $container->import($configDir . '/{services}_' . $this->environment . '.yaml');
        } else {
            $container->import($configDir . '/{services}.php');
        }
    }
}
