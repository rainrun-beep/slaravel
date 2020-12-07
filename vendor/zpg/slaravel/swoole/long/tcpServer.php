<?php
// swoole_get_local_ip(); 获取当前主机的ip数组参数
$server = new Swoole\Server('0.0.0.0', 9503);

// 检测提供了固定包头+包体协议解析,启用后保证接收到完整的数据包, 可以防止粘包
$server->set(array(
    'open_length_check'     => true, //开启
    'package_max_length'    => 1 * 1024 * 1024, //接收最大包的长度
    'package_length_type'   => 'n', //校验包的长度类型
    'package_length_offset' => 0, //从哪一个开始
    'package_body_offset'   => 2, //包头的大小
    'daemonize' => 1
));

//心跳检测，每2s中去检测在3s内没有给我发送消息的连接
// $server->set([
//     'heartbeat_check_interval' => 2, //表示每2秒，遍历所有
//     'heartbeat_idle_time' => 3, // 如果3s内没有向服务器发送数据，强制关闭
// ]);
 

$server->on('connect', function($server, $fd){
    echo "connection open: {$fd}\n";
});

 //接收到数据时调用
$server->on('receive', function($server, $fd, $reactor_id, $data) {
    //echo "接收到信息".$data."\n";
    //$server->send($fd, "Swoole: {$data}");
    //$server->close($fd);

    echo "接收到的数据:$data\n"; //粘包
    //echo $fd; //拆包
    $server->send($fd, "Swoole: ok\n");
});

$server->on('close', function ($server, $fd) {
    echo "connection close: {$fd}\n";
});

$server->start();
