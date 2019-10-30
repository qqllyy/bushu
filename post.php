<html class="hb-loaded"><head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="format-detection" content="telephone=no">
<title>修改结果</title>
<link href="//cdn.bootcss.com/FrozenUI/1.3.0/css/frozen.css" rel="stylesheet">
<script src="//cdn.bootcss.com/FrozenUI/1.3.0/lib/zepto.min.js"></script>
<script src="//cdn.bootcss.com/FrozenUI/1.3.0/js/frozen.js"></script>
</head>
<body ontouchstart="" huaban_collector_injected="true">
<header class="ui-header ui-header-positive ui-border-b">
<i class="ui-icon-return" onclick="history.back()"></i><h1>修改结果</h1><a href="/"><button class="ui-btn">回首页</button></a>
</header>
<section class="ui-container">


<?php

require __DIR__ . '/Curl.php';
//https://packagist.org/packages/php-curl-class/php-curl-class
 
use \Curl\Curl;
$curl = new Curl();
 
$salt='8061FD';//salt

$account=$_POST['account'];//绑定微信的卓易账号
$bushu=$_POST['bushu'];//提交步数



$timestamp = time();
$sign = md5($account . $salt . $timestamp);
 
$host = "http://weixin.droi.com/health/phone/index.php/SendWechat/getWxOpenid";

       
$curl->POST($host,
    array(
        'accountId' => $account,
        'timeStamp' => $timestamp,
        'sign' => $sign
    ));

if ($curl->error) {
    die('Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n");
} else {
    $rep = json_decode( $curl->response, true );
    if( $rep['code']!==0 ) {
        die( 'getWxOpenid:'.$rep['messsage']."\n" );
    }
    $openid=$rep['openid'];
    echo "WeChatOpenId: {$openid}\n";
}
echo "</br>";
echo "</br>";
$timestamp = time();
$sign=md5($account.$salt.$bushu.$salt.$timestamp.$salt.$openid);
$host = "http://weixin.droi.com/health/phone/index.php/SendWechat/stepSubmit";
$curl->post($host,
    array(
        'accountId' => $account,
        'jibuNuber'=>$bushu,
        'timeStamp' => $timestamp,
        'sign' => $sign
    ));
 
if ($curl->error) {
    die('stepSubmit: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n");
} else {
    $rep = json_decode($curl->response,true);
    echo "stepSubmit: ".$rep['messsage'];
}
 
$curl->close();




