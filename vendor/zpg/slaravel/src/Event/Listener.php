<?php

namespace Slaravel\Event;
use Slaravel\Foundation\Application;

class Listener {
    protected $name = 'listener';

    // public function __construct(Application $app) {
    //     $this->app = $app;
    // }

    public function __construct() {

    }

    public function handle() {}

    /**
     * 获取监听器标识名称
     * @return string
     */
    public function getName() {
        return $this->name;
    }

}