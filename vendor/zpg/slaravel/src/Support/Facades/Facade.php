<?php
namespace Slaravel\Support\Facades;

class Facade {

    //解析过得实例
    protected static $resolvedInstance = [];
    protected static $app;

    public static function __callStatic($method, $arguments) {
        # 获取实例 (谁调用这个静态方法 static就是谁 如果是self就一直是Facade)
        $instance = static::getFacadeRoot();
        return $instance->$method(...$arguments);
    }

    #返回门面实例
    public static function getFacadeRoot() {
        //解析门面实例
        return static::resolveFacadeInstance(static::getFacadeAccessor());
    }

    /**
     * 获取门面存取器
     * 每个子类都有这个方法
     */
    public static function getFacadeAccessor() {}

    /**
     * 解析门面实例对象
     */
    public static function resolveFacadeInstance($object) {
        if (is_object($object)) {
            return $object;
        }

        if (isset(static::$resolvedInstance[$object])) {
            return static::$resolvedInstance[$object];
        }

        return static::$resolvedInstance[$object] = static::$app->make($object);
    }

    /**
     * 注入app
     */
    public static function setFacadeApplication($app) {
        static::$app = $app;
    }

}