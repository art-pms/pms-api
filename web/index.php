<?php

// Omit trailing slash (i.e. /user/ === /user)
$_SERVER['REQUEST_URI'] = rtrim($_SERVER['REQUEST_URI'], '/');

$env = strtolower(getenv('APP_ENV')) ? : 'dev';

if (file_exists(__DIR__ . '/bootstrap-'.$env.'.php')) {
    $app = require __DIR__.'/bootstrap-'.$env.'.php';
}
else {
    $app = require __DIR__ . '/bootstrap.php';
}

$app->get('/api/info', function (Application $app) {
    return $app->json([
        'status' => true,
        'info'   => [
            'name'    => 'Ivan',
            'surname' => 'Kuznetsov'
        ]]);
});

$app->run();
