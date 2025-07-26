<?php

use App\Kernel;
use Symfony\Component\HttpFoundation\Request;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$kernel = new Kernel(
    getenv('APP_ENV') ?: 'prod',
    filter_var(getenv('APP_DEBUG') ?: false, FILTER_VALIDATE_BOOLEAN)
);

$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
