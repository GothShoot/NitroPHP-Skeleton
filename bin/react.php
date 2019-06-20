#!/usr/bin/env php
<?php

ini_set('memory_limit', '1G');
set_time_limit(0);

use App\Kernel;
use Nyholm\Psr7\Factory\Psr17Factory;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;

require __DIR__.'/../vendor/autoload.php';

// Initialize kernel
$kernel = new Kernel();
$psr17Factory = new Psr17Factory();
$httpFoundationFactory = new HttpFoundationFactory();
$psr7Factory = new PsrHttpFactory($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);

// Callback for the loop
$callback = function(Psr\Http\Message\ServerRequestInterface $request) use ($kernel, $httpFoundationFactory, $psr7Factory)
{
    $kernel->incrementCount();
    try
    {
        // Convert the Psr Request to Symfony Request
        $symfonyRequest =  $httpFoundationFactory->createRequest($request);
        // Track request count per running instance of kernel
        $symfonyRequest->attributes->set('count', $kernel->getCount());
        $response = $kernel->handle($symfonyRequest);
    }
    catch (\Throwable $e) 
    {
        return new React\Http\Response(
                500,
                ['Content-Type' => 'text/plain'],
                $e->getMessage()
        );
    }
    // Convert the Symfony response to Psr response
    return $psr7Factory->createResponse($response);
};

$loopManager = new \App\LoopManager($kernel);
$loop = $loopManager->create($callback);
$loop->run();