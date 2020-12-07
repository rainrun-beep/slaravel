<?php
namespace Slaravel\Foundation\Bootstrap;
use Slaravel\Foundation\Application;

class RegisterProviders {
    public function bootstrap(Application $app) {
        $app->registerConfigredProviders();
    }
}