<?php
namespace Slaravel\Event;

class Event {
    protected $listener;
    protected $events;

    /**
     * 设置监听器
     * @param $listener 监听器标识
     * @param $callback 监听器
     */
    public function listener($listener, $callback) {
        $this->listener = $listener = strtolower($listener);
        $this->events[$listener] = ['callback' => $callback];
    }

    /**
     * 分发事件
     * @param  $listener   监听器标识
     * @param  array $param  参数
     * @return bool
     */
    public function dispatch($listener, $param = []) {
        $listener = strtolower($listener);
        if ($this->events[$listener]) {
            # array(new class(), 'function name'());
            ($this->events[$listener]['callback'](...[$param]));
            return true;
        }
    }
}