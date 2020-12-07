<?php
//中间件过滤作用, 事件打补丁
use Slaravel\Support\Facades\Route;
Route::get('test3', function(){
    echo '路由闭包的执行';
});

Route::get('hello', 'Hello@index');
Route::get('/', function() {
    echo 'index';
});

// Route::get('/', function () {
//     return view('welcome');
// });