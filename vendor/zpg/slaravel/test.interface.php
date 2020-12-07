<?php

//起到约束作用 可以提前知道方法和参数
interface DB {
    public function select();
}

class Mysql implements DB {
    public function select () {
        echo 'mysql查询';
    }
}
$mysql = new Mysql();

class Oracle implements DB {
    public function select () {
        echo 'oracle查询';
    }
}
$oracle = new Oracle();

class Person {
    public function query(DB $db) {  //如果不用接口, Mysql $db,  Oracle $db;
        $db->select();
    }
}

$per = new Person();
$per->query($oracle);