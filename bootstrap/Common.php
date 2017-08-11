<?php
/**
 * Created by PhpStorm.
 * User: JeemuZhou
 * Date: 2017/6/26
 * Time: 11:48
 */


/**
 * 获取配置
 * @param $conf
 * @return mixed
 */
function config($conf)
{
    $conf = explode('.', $conf);
    $confArr = Yaf_Registry::get('config');
    $result = null;
    foreach ($conf as $value) {
        if (empty($value)) {
            $result = null;
            break;
        }
        if (isset($confArr[$value])) {
            $confArr = $confArr[$value];
            $result = $confArr;
        } else {
            $result = null;
            break;
        }
    }

    return $result;
}


/**
 * 返回前端
 * @param null $data
 * @param string $msg
 * @param int $status
 */
function ajaxReturn($data = null, $msg = 'success', $status = 1)
{
    header('Content-Type:application/json; charset=utf-8');
    $callback = isset($_GET['callback'])?$_GET['callback']:null;
    if ($callback) {
        die($callback . '(' . json_encode([
                'data' => $data,
                'msg' => $msg,
                'status' => $status
            ]) . ')');
    } else {
        die(json_encode([
            'data' => $data,
            'msg' => $msg,
            'status' => $status
        ]));
    }
}

/**
 * 产生随机数
 * @param int $len
 * @return null|string
 */
function randStr($len = 8){
    $str = null;
    $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
    $max = strlen($strPol)-1;

    for($i=0;$i<$len;$i++){
        $str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
    }

    return $str;
}

/**
 * 获取浏览器ip
 */
 function getClientIp(){
    $ip = '';
    if (isset($_SERVER['HTTP_X_REAL_IP'])){
        $ip = $_SERVER['HTTP_X_REAL_IP'];
    }elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }elseif (isset($_SERVER['REMOTE_ADDR'])){
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}


/**
 * 判断是安卓还是Ios
 * @return string
 */
 function getClientPlatform(){
    $result = 'PC';
    if(strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone')||strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')){
        $result = 'IOS';
    }else if(strpos($_SERVER['HTTP_USER_AGENT'], 'Android')){
        $result = 'Android';
    }
    return $result;
}


/**
 * 判断是否是微信
 * @return bool
 */
 function isWeixin() {
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
        return true;
    } else {
        return false;
    }
}

/**
 * 判断是否是手机
 * @return bool
 */
 function isMobile() {
    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
    if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
        return true;
    }
    // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
    if (isset($_SERVER['HTTP_VIA'])) {
        // 找不到为flase,否则为true
        return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
    }
    // 脑残法，判断手机发送的客户端标志,兼容性有待提高。其中'MicroMessenger'是电脑微信
    if (isset($_SERVER['HTTP_USER_AGENT'])) {
        $clientkeywords = array('nokia','sony','ericsson','mot','samsung','htc','sgh','lg','sharp','sie-','philips','panasonic','alcatel','lenovo','iphone','ipod','blackberry','meizu','android','netfront','symbian','ucweb','windowsce','palm','operamini','operamobi','openwave','nexusone','cldc','midp','wap','mobile','MicroMessenger');
        // 从HTTP_USER_AGENT中查找手机浏览器的关键字
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
            return true;
        }
    }
    // 协议法，因为有可能不准确，放到最后判断
    if (isset ($_SERVER['HTTP_ACCEPT'])) {
        // 如果只支持wml并且不支持html那一定是移动设备
        // 如果支持wml和html但是wml在html之前则是移动设备
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
            return true;
        }
    }
    return false;
}