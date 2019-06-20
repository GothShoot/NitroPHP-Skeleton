<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\Debug\Debug;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\RouteCollectionBuilder;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    private const CONFIG_EXTS = '.{php,xml,yaml,yml}';

    private $count = 0;

    public function __construct()
    {
        $this->init();
        $env = $_SERVER['APP_ENV'] ?? 'prod';
        $debug = (bool) ($_SERVER['APP_DEBUG'] ?? ('prod' !== $env));
        if ($debug)
        {
            umask(0000);
            Debug::enable();
        }
        parent::__construct($env, $debug);
        $this->boot();
    }

    /**
     * init ENV
     */
    private function init()
    {
        if (!isset($_SERVER['APP_ENV']))
        {
            if (!class_exists(Dotenv::class))
            {
                throw new \RuntimeException('APP_ENV environment variable is not defined. You need to define environment variables for configuration or add "symfony/dotenv" as a Composer dependency to load variables from a .env file.');
            }
            (new Dotenv())->load(__DIR__.'/../.env');
        }
    }

    /**
     * Get loop timer parameter from container
     *
     * @return array $LoopTimerParameter
     */
    public function getLoopTimerConfig()
    {
        return (
            $this->container->hasParameter('loop.timer') ?
                $this->container->getParameter('loop.timer') :
                []
        );
    }

    public function getSocketServerConfig()
    {
        return (
            $this->container->hasParameter('loop.server') ?
                $this->container->getParameter('loop.server') :
                ['port' => 8000, 'url' => 'http://127.0.0.1']

        );
    }

    public function registerBundles(): iterable
    {
        $contents = require $this->getProjectDir().'/config/bundles.php';
        foreach ($contents as $class => $envs) {
            if ($envs[$this->environment] ?? $envs['all'] ?? false) {
                yield new $class();
            }
        }
    }

    public function getProjectDir(): string
    {
        return \dirname(__DIR__);
    }

    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader): void
    {
        $container->addResource(new FileResource($this->getProjectDir().'/config/bundles.php'));
        $container->setParameter('container.dumper.inline_class_loader', true);
        $confDir = $this->getProjectDir().'/config';

        $loader->load($confDir.'/{packages}/*'.self::CONFIG_EXTS, 'glob');
        $loader->load($confDir.'/{packages}/'.$this->environment.'/**/*'.self::CONFIG_EXTS, 'glob');
        $loader->load($confDir.'/{services}'.self::CONFIG_EXTS, 'glob');
        $loader->load($confDir.'/{services}_'.$this->environment.self::CONFIG_EXTS, 'glob');
    }

    protected function configureRoutes(RouteCollectionBuilder $routes): void
    {
        $confDir = $this->getProjectDir().'/config';

        $routes->import($confDir.'/{routes}/'.$this->environment.'/**/*'.self::CONFIG_EXTS, '/', 'glob');
        $routes->import($confDir.'/{routes}/*'.self::CONFIG_EXTS, '/', 'glob');
        $routes->import($confDir.'/{routes}'.self::CONFIG_EXTS, '/', 'glob');
    }

    public function incrementCount()
    {
        $this->count++;
    }
    
    public function getCount()
    {
        return $this->count;
    }
}
