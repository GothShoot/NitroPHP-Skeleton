<?php


namespace App;

use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use React\Http\Server;
use React\Socket\Server as Socket;

/**
 * Class LoopManager.
 *
 * Manage event loop from ReactPHP and integrate it with Symfony 4 kernel.
 *
 * @package App\Server
 */
class LoopManager
{
    /**
     * The loop.
     *
     * @var LoopInterface $loop
     */
    private $loop;

    /**
     * Primary kernel.
     *
     * @var Kernel $kernel
     */
    private $kernel;

    /**
     * LoopManager constructor.
     *
     * @param Kernel $kernel
     */
    public function __construct(Kernel $kernel)
    {
        $this->kernel = $kernel;
        $this->loop = Factory::create();
    }

    /**
     * Create fully configured event loop.
     *
     * @param $callback
     * @return mixed
     */
    public function create($callback)
    {
        $this->attachTimer();
        $this->configServer($callback);
        return $this->loop;
    }

    /**
     * Attach periodic timer to loop from config.
     */
    private function attachTimer()
    {
        $kernel = $this->kernel;
        $params = $kernel->getLoopTimerConfig();
        foreach ($params as $param)
            $this->loop->addPeriodicTimer($param['timer'], function($param) use ($kernel)
                {
                    $application = new Application($kernel);
                    $application->run($param['input']);
                }
            );
    }

    /**
     * Configure Socket server from config.
     *
     * @param $callback
     */
    private function configServer($callback)
    {
        $params = $this->kernel->getSocketServerConfig();
        $server = new Server($callback);
        $socket = new Socket($params['port'], $this->loop);
        $server->listen($socket);
        echo 'System Online '.$params['url'].':'.$params['port'].'\n';
    }
}