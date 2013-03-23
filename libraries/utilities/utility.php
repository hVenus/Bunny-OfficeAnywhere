<?php
/**
 * 这个文件目前暂定为一些有用的工具方法。
 * 先放这里，以后再整理。
 * Author: hVenus
 * Create Date: 2013/3/23
 */

// TODO 将下面的函数归类到正确的类中。


/**
 *判断是否是通过手机访问
 * @return bool  true：是手机或移动设备;false:不是手机
 */
function isMobile() {

    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
    if (isset ($_SERVER['HTTP_X_WAP_PROFILE'])) {
        var_dump($_SERVER['HTTP_X_WAP_PROFILE']);
        return true;
    }

    //如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
    if (isset ($_SERVER['HTTP_VIA'])) {
        //找不到为flase,否则为true
        return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
    }

    //判断手机发送的客户端标志,兼容性有待提高
    if (isset ($_SERVER['HTTP_USER_AGENT'])) {
        $clientkeywords = array (
            'nokia',
            'sony',
            'ericsson',
            'mot',
            'samsung',
            'htc',
            'sgh',
            'lg',
            'sharp',
            'sie-',
            'philips',
            'panasonic',
            'alcatel',
            'lenovo',
            'iphone',
            'ipod',
            'ipad',
            'blackberry',
            'meizu',
            'android',
            'netfront',
            'symbian',
            'ucweb',
            'windowsce',
            'palm',
            'operamini',
            'operamobi',
            'openwave',
            'nexusone',
            'cldc',
            'midp',
            'wap',
            'mobile'
        );
        // 从HTTP_USER_AGENT中查找手机浏览器的关键字
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
            return true;
        }
    }
    //协议法，因为有可能不准确，放到最后判断
    if (isset ($_SERVER['HTTP_ACCEPT'])) {
        // 如果只支持wml并且不支持html那一定是移动设备
        // 如果支持wml和html但是wml在html之前则是移动设备
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
            return true;
        }
    }
    return false;
}

/**
 * 获取浏览器版本
 * @return mixed
 */
function GetBrowserVersion(){
    if(strpos($_SERVER['HTTP_USER_AGENT'], "MSIE 8.0")) {
        //$visitor_browser = "Internet Explorer 8.0";
    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], "MSIE 7.0")) {
        $visitor_browser = "Internet Explorer 7.0";
    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], "MSIE 9.0")) {
        //$visitor_browser = "Internet Explorer 9.0";
    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], "MSIE 6.0")) {
        $visitor_browser = "Internet Explorer 6.0";
    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], "MSIE 5.5")) {
        $visitor_browser = "Internet Explorer 5.5";
    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], "MSIE 5.0")) {
        $visitor_browser = "Internet Explorer 5.0";
    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], "MSIE 4.01")) {
        $visitor_browser = "Internet Explorer 4.01";
    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], "NetCaptor")) {
        $visitor_browser = "NetCaptor";
    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], "Netscape")) {
        $visitor_browser = "Netscape";
    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], "Lynx")) {
        $visitor_browser = "Lynx";
    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], "Opera")) {
        $visitor_browser = "Opera";
    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], "Konqueror")) {
        $visitor_browser = "Konqueror";
    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], "Mozilla/5.0")) {
        $visitor_browser = "Mozilla";
    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], "MSIE")) {
        //$visitor_browser = "Internet Explorer";
    } else {
        $visitor_browser = "others";
    }
    return $visitor_browser;
}

/**
 * 判断是否为IE
 * @return bool
 */
function isIE(){
    $browser = GetBrowserVersion();
    if($browser == "Internet Explorer" || $browser == 'Internet Explorer 9.0' || $browser == 'Internet Explorer 8.0' || $browser == 'Internet Explorer 7.0' || $browser == 'Internet Explorer 6.0' || $browser == 'Internet Explorer 5.5' || $browser == 'Internet Explorer 5.0' || $browser == 'Internet Explorer 4.01'){
        return true;
    }
    return false;
}