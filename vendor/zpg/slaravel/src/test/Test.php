<?php

namespace Slaravel\Test; //其中的slaravel对应的是composer.json中的src路径即是src路径
use \Slaravel\Contracts\Test as TestC;

class Test implements TestC {
    public function __construct() {
        echo '测试自动加载';
    }

    public function test() {
        echo '<br>';
        echo 'test的test';
    }
}