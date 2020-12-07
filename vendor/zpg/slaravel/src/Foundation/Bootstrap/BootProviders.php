<?php
namespace Slaravel\Foundation\Bootstrap;
use Slaravel\Foundation\Application;

class BootProviders {
    public function bootstrap(Application $app) {
       $app->boot(); 
    }
}