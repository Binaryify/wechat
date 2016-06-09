<?php
header("Access-Control-Allow-Origin:*");
$appid="wx510c053a9dfb3022";  //wx50fdc102b073ce6f
$secret="de40f08c9b027d61e26079861d2cc41f";  //009dabecc9fd46646844f54cbef8bd69
function getToken(){
    $str=file_get_contents('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$GLOBALS['appid'].'&secret='.$GLOBALS['secret']);
    $obj=json_decode($str);
    $token=$obj->{'access_token'}; //获取access_token
    $file = fopen('token.txt', 'w');
    fwrite($file,$token);
    fclose($file);
//读取保存的access_token
    $fp = fopen("token.txt", "r");
    $fileContent= fgets($fp);
    $access_token=$fileContent;
    fclose($fp);
    return $access_token;

}
    function getTokenFromData(){
        $fp = fopen("token.txt", "r");
        $fileContent= fgets($fp);
        $access_token=$fileContent;
        fclose($fp); //获取储存的token
        return $access_token;
    }
// $access_token=getToken();
function getTicket(){
	$access_token=getTokenFromData();
	$url=file_get_contents("https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=$access_token&type=jsapi");
	$obj=json_decode($url);
    $errcode=$obj->{'errcode'};

    if($errcode){
        $access_token=getToken();
        $url=file_get_contents("https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=$access_token&type=jsapi");
        $obj=json_decode($url);
    }
        $jsapi_ticket=$obj->{'ticket'};
        $file = fopen('ticket.txt', 'w');
        fwrite($file,$jsapi_ticket);
        fclose($file);
        $fp = fopen("ticket.txt", "r");
        $fileContent= fgets($fp);
        $ticket=$fileContent;
        fclose($fp);
        return $ticket;
}
//ticket 7200s失效，ticket是否有获取上限未知
function createNonceStr($length = 16) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $str = "";
    for ($i = 0; $i < $length; $i++) {
      $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    return $str;
  }

 // $access_token=getToken();
 $ticket=getTicket();

 $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
 $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
 // $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
 $timestamp=time();
 $nonceStr=createNonceStr($length = 16);
 $string = "jsapi_ticket=$ticket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

 $signature = sha1($string);
 // $WXInfo=array('appid'=>$appid,'time'=>time(),'nonceStr'=>$nonceStr,'signature'=>$signature);
 // echo json_encode($WXInfo);
?>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" charset="utf-8"></script>
<script type="text/javascript">
wx.ready(function () {
   wx.hideOptionMenu();
});

</script>

<script type="text/javascript">



    wx.config({
    debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
    appId: 'wx50fdc102b073ce6f', // 必填，公众号的唯一标识
    timestamp: '<?php echo time();?>', // 必填，生成签名的时间戳
    nonceStr: '<?php echo $nonceStr;?>', // 必填，生成签名的随机串
    signature: '<?php echo $signature;?>',// 必填，签名，见附录1
    jsApiList: [
        'checkJsApi',
        'onMenuShareTimeline',
        'onMenuShareAppMessage',
        'onMenuShareQQ',
        'onMenuShareWeibo',
        'hideMenuItems',
        'showMenuItems',
        'hideAllNonBaseMenuItem',
        'showAllNonBaseMenuItem',
        'translateVoice',
        'startRecord',
        'stopRecord',
        'onRecordEnd',
        'playVoice',
        'pauseVoice',
        'stopVoice',
        'uploadVoice',
        'downloadVoice',
        'chooseImage',
        'previewImage',
        'uploadImage',
        'downloadImage',
        'getNetworkType',
        'openLocation',
        'getLocation',
        'hideOptionMenu',
        'showOptionMenu',
        'closeWindow',
        'scanQRCode',
        'chooseWXPay',
        'openProductSpecificView',
        'addCard',
        'chooseCard',
        'openCard']

})




</script>

