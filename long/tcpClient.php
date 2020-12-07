<?php 
//SWOOLE_SOCK_TCP | SWOOLE_KEEP 长连接
$client = new Swoole\Client(SWOOLE_SOCK_TCP);

if (!$client->connect('172.26.240.108', 9503, -1)) {
    exit("connect failed. Error: {$client->errCode}\n");
}


$end = "\r\n";
for($i = 0; $i < 100; $i++) {
    $client->send("hello_world_".$end);
}

//每2s触发一次

// swoole_timer_tick(2000, function ($timer_id) use ($client) {
//  $client->send(1);
//     $client->recv();
//     echo "tick-2000ms\n";
// });


/**
 * 当发送数据包很大的时候会拆分成多个包
 */
//$client->send(str_repeat('123456', 100000)); 

//$client->send("hello world\n"); 
//echo $client->recv(); //从服务器端接收数据
$client->close();  //关闭客户端服务
