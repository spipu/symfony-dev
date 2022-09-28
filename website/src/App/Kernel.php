<?php

namespace App;

use App\DependencyInjection\AppExtension;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    /**
     * @var string
     */
    private $projectDir;

    /**
     * Gets the application root dir (path of the project's composer file).
     *
     * @return string The project root dir
     */
    public function getProjectDir()
    {
        if (null === $this->projectDir) {
            $this->projectDir = dirname(dirname(__DIR__));
        }

        return $this->projectDir;
    }

    /**
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

        $confDir = $this->getProjectDir() . '/config';

        $container->import($confDir . '/{packages}/*.yaml');
        $container->import($confDir . '/{packages}/' . $this->environment . '/*.yaml');

        if (is_file($confDir . '/services.yaml')) {
            $container->import($confDir . '/services.yaml');
            $container->import($confDir . '/{services}_' . $this->environment . '.yaml');
        } else {
            $container->import($confDir . '/{services}.php');
        }
    }

    /**
     * @param RoutingConfigurator $routes
     * @return void
     * @SuppressWarnings(PMD.ElseExpression)
     */
    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $confDir = $this->getProjectDir() . '/config';

        $routes->import($confDir . '/{routes}/' . $this->environment . '/*.yaml');
        $routes->import($confDir . '/{routes}/*.yaml');

        if (is_file($confDir . '/routes.yaml')) {
            $routes->import($confDir . '/routes.yaml');
        } else {
            $routes->import($confDir . '/{routes}.php');
        }
    }
}
