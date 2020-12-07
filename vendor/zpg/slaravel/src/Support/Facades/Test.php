<?php

namespace Slaravel\Support\Facades;

class Test extends Facade{
    public static function getFacadeAccessor() {
        return 'test';
    } 
}