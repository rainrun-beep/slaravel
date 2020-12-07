<?php
require_once __DIR__ .'/../vendor/autoload.php';
echo '<pre>';
$app = new Slaravel\Foundation\Application($_ENV['APP_BASE_PATH'] ?? dirname(__DIR__));  
//使用门面
// \Slaravel\Support\Facades\Test::test();
// $app->singleton('kernel', \App\Http\Kernel::class);
$kernel = $app->make('kernel', $app);
$kernel->handle(\Slaravel\Http\Request::capture()); //capture 捕获
// $config = $app->make('config')->all();
// $app->registerConfigredProviders();
