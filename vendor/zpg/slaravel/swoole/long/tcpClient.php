<?php 
//SWOOLE_SOCK_TCP | SWOOLE_KEEP 长连接
$client = new Swoole\Client(SWOOLE_SOCK_TCP);

if (!$client->connect('172.26.240.108', 9503, -1)) {
    exit("connect failed. Error: {$client->errCode}\n");
}

//配置是为了防止服务器发包的粘包问题
// $client->set(array(
//     'open_length_check'     => true, //开启
//     'package_max_length'    => 1 * 1024 * 1024, //接收最大包的长度
//     'package_length_type'   => 'n', //校验包的长度类型
//     'package_length_offset' => 0, //从哪一个开始
//     'package_body_offset'   => 2, //包头的大小
// ));
// pack(); //将数据打包成二进制字符串
// unpack(); //解析二进制字符串
// $end = "\r\n";
for($i = 0; $i < 10; $i++) {
    //$client->send("hello_world_".$end);
    $context = '123';
    //利用pack打包长度
    $len = pack('n', strlen($context));
    //组包
    $send = $len . $context;
    $client->send($send);
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
echo $client->recv(); //从服务器端接收数据
$client->close();  //关闭客户端服务
