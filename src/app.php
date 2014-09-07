<?php

use Pms\Api\Application;
use Wildsurfer\Provider\MongodmServiceProvider;

// Set default timezone
date_default_timezone_set('UTC');

// So script can override current __DIR__ (used @ workers)
if (!defined('__ACTIVE_DIRECTORY__')) {
    define('__ACTIVE_DIRECTORY__', __DIR__);
}

$app = new Application();

$app['env'] = $env;
$app['api.interface'] = 'unknown';

// Error handling
error_reporting(E_ALL ^ E_DEPRECATED);

//---------------------
// Silex
//---------------------

// ServiceControllerServiceProvider enables us to lazy load controller classes as services
$app->register(new Silex\Provider\ServiceControllerServiceProvider());

// Validator Service
$app->register(new Silex\Provider\ValidatorServiceProvider());

//---------------------
// Config
//---------------------

// Config
$app->register(new \Igorw\Silex\ConfigServiceProvider(__ACTIVE_DIRECTORY__ . '/../config/config.php'));
//$app->register(new \Igorw\Silex\ConfigServiceProvider(__ACTIVE_DIRECTORY__ . '/../config/config-' . $env . '.php'));

//---------------------
// CORS
//---------------------
$app->register(new JDesrosiers\Silex\Provider\CorsServiceProvider(), array());

//---------------------
// MongoDM
//---------------------
$app->register(new MongodmServiceProvider(), array(
    'mongodm.host' => $app['db']['host'],
    'mongodm.db' => $app['db']['db'],
    'mongodm.options' => $app['db']['options']
));
$app['mongodm']->connect();

return $app;
