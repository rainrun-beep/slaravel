<?php
require_once './vendor/autoload.php';

#创建容器实例 ::class 带命名空间的类名
$ioc = new \Slaravel\Container\Container();
// $ioc->bind('renting', \Slaravel\Test\Test::class); //绑定
// $ioc->singleton('renting', \Slaravel\Test\Test::class);//singleton 只有一个实例 只实例化一次
// $ioc->getBind();
// var_dump($ioc->make('renting')); //解析
// var_dump($ioc->make('renting'));


//通过契约(接口)绑定
// $ioc->bind(\Slaravel\Contracts\Test::class, \Slaravel\Test\Test::class);
// var_dump($ioc->make(\Slaravel\Contracts\Test::class));
$app = new \Slaravel\Foundation\Application();