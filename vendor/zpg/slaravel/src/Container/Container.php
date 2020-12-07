<?php
namespace Slaravel\Container;

/**
 * 装服务:
 * 容器:
 *      bind:
 *      make:
 */
class Container {
    # 容器的本质 => 数组
    protected $bindings = []; //容器的绑定参数(不是实例)
    
    protected $instances = []; //共享容器(实例)
    
    protected static $instance;

    /**
     * 绑定对象
     * @param $abstract 标识
     * @param null $concrete 具体对象 - 字符串，闭包，实例
     */
    public function bind ($abstract, $concrete = null, $shared = false) {
        #绑定 -> 对于数组添加值
        $this->bindings[$abstract]['shared'] = $shared;  //是否共享 共享将这个值放入到数组中,以后想用的时候再取出来,所以都是同一个实例
        $this->bindings[$abstract]['concrete'] = $concrete; //带命名空间的类名,用来过后实例
    }

    /**
     * 单例模式创建
     * @param $abstract 标识
     * @param $concrete 创建的实例
     * @param $shared 是否共享
     * return null;
     */
    public function singleton ($abstract, $concrete = null, $shared = true) {
        #绑定 -> 对于数组添加值
        $this->bind($abstract, $concrete, $shared);
    }

    /**
     * 测试打印容器中的实例
     */
    public function dump($param) {
        echo '<pre>';
        var_dump($this->$param);
    }

    /**
     * 解析绑定
     * 因为make是public,所以任何对象都可以调用, 所以再调用reslove进行解析,
     * 提供一个给外接操作的接口
     */
    public function make($abstract, $parameters = []) {
        return $this->reslove($abstract, $parameters);
    }

    /**
     * 解析对象
     * @param $abstract 标识
     * @param [] $parameters 具体参数
     */
    protected function reslove($abstract, $parameters = []) {
        //1. 判断实例是否存在
        if(isset($this->instances[$abstract]) && !empty($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        $object = $this->getConcrete($abstract);
        //$object = $this->bindings[$abstract]['concrete'];

        //如果实例是闭包,返回调用的结果
        if ($object instanceof \Closure) {
            return $object();
        }
        
        //如果是字符串, 实例化字符串对应的类
        if(!is_object($object)) {
            // $parameters = ['jack', 18];
            //...的意思是将参数数组分别给$object初始化函数的参数,如果传参大于形参多的形参不显示
            $object = new $object(...[$parameters]);
        }

        if(isset($this->bindings[$abstract]['shared'])) {
            $this->instances[$abstract] = $object;
        }
        return $object;
    }

    /**
     * 根据参数将共享容器中添加实例
     * @param $abstract 标识
     * @param $instance 实例
     * @return null
     * 
     */
    public function instance($abstract, $instance) {
        //如果有标识清除
        $this->removeBingings($abstract);
        $this->instances[$abstract] = $instance;
    }

    /**
     * 静态方式设置app
     * @param null $container
     * @return null
     */
    public static function setInstance($container = null) {
        return static::$instance = $container;
    }

    /**
     * 静态方式获取绑定的值
     * @return array
     */
    public static function getInstance() {
        if(is_null(static::$instance)) {
            static::$instance = new static;
        }
        return static::$instance;
    }

    /**
     * 如果普通容器中有这个标识将它移除
     * @param $abstract 标识
     * @return null
     */
    public function removeBingings($abstract) {
        if (!isset($this->bindings[$abstract])) {
            unset($this->bindings[$abstract]);
        }
    }

    /**
     * 获取普通绑定的内容
     * @abstract 容器中的标识
     * @return 返回标识对应带命名空间的类 
     */
    public function getConcrete($abstract) {
        //存在返回绑定的带命名空间的类名
        if ($this->has($abstract)) {
            return $this->bindings[$abstract]['concrete'];
        }
        //不存在返回标识
        return $abstract;
    }

     /**
     * 判断容器中是否有这个标识
     * @param $abstract 标识
     * @return bool
     */
    public function has($abstract) {
        return (isset($this->bindings[$abstract]['concrete']));
    }

}