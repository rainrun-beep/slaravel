<?php
namespace App\Http\Controllers;

class Hello {
    public function index() {
        echo '<br>控制器方法的index<br>';
        # 记录日志
        app('event')->dispatch('log', '你猜呢');
    }
}