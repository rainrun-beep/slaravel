<?php

namespace Slaravel\Support\Facades;

class Route extends Facade{
    public static function getFacadeAccessor() {
        return 'router';
    } 
}