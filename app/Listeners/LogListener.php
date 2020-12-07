<?php
namespace App\Listeners;
use Slaravel\Event\Listener;

class LogListener extends Listener {
    
    #监听器标识
    protected $name = 'log'; 

    #记录日志
    public function handle($request = null) {
        $request = $request ?? ' ';
        echo $request.'<br>';
    }
}