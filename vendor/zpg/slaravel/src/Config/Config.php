<?php

namespace Slaravel\Config;

class Config {
    protected $items = [];
    public function phpParser($configPath) {
        $data = [];
        $files = scandir($configPath);
        foreach($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            $filename = stristr($file, '.php', true); //返回$file中的.php字符串之前的字符串
            $data[$filename] = include_once $configPath.'/'.$file; //获取对应的配置文件中的信息数据
        }
        $this->items = $data;
        return $this;
    }

    //测试使用
    public function all() {
        return $this->items;
    }

    # 获取config下的文件对应带命名空间的类名
    public function get($keys) {  
        $data = $this->items;
        //如果传过来的数据是一个app, 就可以获取app的所有数据, 如果是app.providers,就可以获取app下的providers
        //explode加\是获取全局中的explode函数, 避免使用当前类中有explode
        foreach(\explode('.', $keys) as $key) {
            $data = $data[$key];
        }
        return $data;
    }
}