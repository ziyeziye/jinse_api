<?php


/**
 * 16进制转10进制
 * @param string $hex
 * @return int|string
 */
function HexDec2(string $hex)
{
    $dec = 0;
    $len = strlen($hex);
    for ($i = 1; $i <= $len; $i++) {
        $dec = bcadd($dec, bcmul(strval(hexdec($hex[$i - 1])), bcpow('16', strval($len - $i))));
    }
    return $dec;
}


/**
 * inarray不区分大小写
 * @param $str
 * @param $address_arr
 * @return bool
 */
function addressInArray($str, $address_arr)
{
    foreach ($address_arr as &$v) {
        $v = strtolower($v);
    }
    return in_array(strtolower($str), $address_arr);
}


/**
 * 小数小于0.0001并去掉多余0问题
 * @param $num
 * @return mixed
 */
function float_format($num)
{
    $num = explode('.', $num);
    if (count($num) == 1) {
        return $num[0];
    }
    $de = $num[1];
    $de = rtrim($de, 0);
    if (strlen($de) > 0) {
        return $num[0] . '.' . $de;
    } else {
        return $num[0];
    }
}


/**
 * 获取客户端IP
 */
function getClientIp()
{
    return $_SERVER['HTTP_ALI_CDN_REAL_IP'] ?? \Request::getClientIp();
}

function get_page($pageName = "pageNum", $sizeName = "pageSize")
{
    $page = request()->input($pageName, null);
    $page = !is_numeric($page) ? 1 : $page;

    $size = request()->input($sizeName, 15);
    $size = !is_numeric($size) ? 15 : $size;

    return [$page, $size];
}

/**
 * 获取客户端真实ip
 */
function getRealIp()
{
    static $realip = NULL;
    if ($realip !== NULL) {
        return $realip;
    }
    if (isset($_SERVER)) {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            foreach ($arr AS $ip) {
                $ip = trim($ip);
                if ($ip != 'unknown') {
                    $realip = $ip;
                    break;
                }
            }
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $realip = $_SERVER['HTTP_CLIENT_IP'];
        } else {
            if (isset($_SERVER['REMOTE_ADDR'])) {
                $realip = $_SERVER['REMOTE_ADDR'];
            } else {
                $realip = '0.0.0.0';
            }
        }
    } else {
        if (getenv('HTTP_X_FORWARDED_FOR')) {
            $realip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_CLIENT_IP')) {
            $realip = getenv('HTTP_CLIENT_IP');
        } else {
            $realip = getenv('REMOTE_ADDR');
        }
    }
    preg_match("/[\d\.]{7,15}/", $realip, $onlineip);
    $realip = !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';
    return isIp($realip) ? $realip : '0.0.0.0';
}

/**
 * 获取客户端手机型号
 * @param $agent //$_SERVER['HTTP_USER_AGENT']
 * @return array[mobile]            手机品牌
 * @return array[mobile_ver]        手机型号
 */
function getClientMobile($agent = '')
{
    if (preg_match('/iPhone\s([^\s|;]+)/i', $agent, $regs)) {
        $mobile_brand = '苹果';
        $mobile_ver = $regs[0];
    } elseif (preg_match('/SAMSUNG|Galaxy|GT-|SCH-|SM-\s([^\s|;]+)/i', $agent, $regs)) {
        $mobile_brand = '三星';
        $mobile_ver = $regs[0];
    } elseif (preg_match('/Huawei|Honor|H60-|H30-\s([^\s|;]+)/i', $agent, $regs)) {
        $mobile_brand = '华为';
        $mobile_ver = $regs[0];
    } elseif (preg_match('/Mi note|mi one\s([^\s|;]+)/i', $agent, $regs)) {
        $mobile_brand = '小米';
        $mobile_ver = $regs[0];
    } elseif (preg_match('/HM NOTE|HM201\s([^\s|;]+)/i', $agent, $regs)) {
        $mobile_brand = '红米';
        $mobile_ver = $regs[0];
    } elseif (preg_match('/Coolpad|8190Q|5910\s([^\s|;]+)/i', $agent, $regs)) {
        $mobile_brand = '酷派';
        $mobile_ver = $regs[0];
    } elseif (preg_match('/ZTE|X9180|N9180|U9180\s([^\s|;]+)/i', $agent, $regs)) {
        $mobile_brand = '中兴';
        $mobile_ver = $regs[0];
    } elseif (preg_match('/OPPO|X9007|X907|X909|R831S|R827T|R821T|R811|R2017\s([^\s|;]+)/i', $agent, $regs)) {
        $mobile_brand = 'OPPO';
        $mobile_ver = $regs[0];
    } elseif (preg_match('/HTC|Desire\s([^\s|;]+)/i', $agent, $regs)) {
        $mobile_brand = 'HTC';
        $mobile_ver = $regs[0];
    } elseif (preg_match('/Nubia|NX50|NX40\s([^\s|;]+)/i', $agent, $regs)) {
        $mobile_brand = '努比亚';
        $mobile_ver = $regs[0];
    } elseif (preg_match('/M045|M032|M355\s([^\s|;]+)/i', $agent, $regs)) {
        $mobile_brand = '魅族';
        $mobile_ver = $regs[0];
    } elseif (preg_match('/Gionee|GN\s([^\s|;]+)/i', $agent, $regs)) {
        $mobile_brand = '金立';
        $mobile_ver = $regs[0];
    } elseif (preg_match('/HS-U|HS-E\s([^\s|;]+)/i', $agent, $regs)) {
        $mobile_brand = '海信';
        $mobile_ver = $regs[0];
    } elseif (preg_match('/Lenove\s([^\s|;]+)/i', $agent, $regs)) {
        $mobile_brand = '联想';
        $mobile_ver = $regs[0];
    } elseif (preg_match('/ONEPLUS\s([^\s|;]+)/i', $agent, $regs)) {
        $mobile_brand = '一加';
        $mobile_ver = $regs[0];
    } elseif (preg_match('/vivo\s([^\s|;]+)/i', $agent, $regs)) {
        $mobile_brand = 'vivo';
        $mobile_ver = $regs[0];
    } elseif (preg_match('/K-Touch\s([^\s|;]+)/i', $agent, $regs)) {
        $mobile_brand = '天语';
        $mobile_ver = $regs[0];
    } elseif (preg_match('/DOOV\s([^\s|;]+)/i', $agent, $regs)) {
        $mobile_brand = '朵唯';
        $mobile_ver = $regs[0];
    } elseif (preg_match('/GFIVE\s([^\s|;]+)/i', $agent, $regs)) {
        $mobile_brand = '基伍';
        $mobile_ver = $regs[0];
    } elseif (preg_match('/Nokia\s([^\s|;]+)/i', $agent, $regs)) {
        $mobile_brand = '诺基亚';
        $mobile_ver = $regs[0];
    } else {
        $mobile_brand = '其他';
        $mobile_ver = '';
    }
    return ['mobile' => $mobile_brand, 'mobile_ver' => $mobile_ver];
}

/**
 * 获取客户端浏览器以及版本号
 * @param $agent //$_SERVER['HTTP_USER_AGENT']
 * @return array[browser]       浏览器名称
 * @return array[browser_ver]   浏览器版本号
 */
function getClientBrowser($agent = '')
{
    $browser = '';
    $browser_ver = '';
    if (preg_match('/OmniWeb\/(v*)([^\s|;]+)/i', $agent, $regs)) {
        $browser = 'OmniWeb';
        $browser_ver = $regs[2];
    }
    if (preg_match('/Netscape([\d]*)\/([^\s]+)/i', $agent, $regs)) {
        $browser = 'Netscape';
        $browser_ver = $regs[2];
    }
    if (preg_match('/safari\/([^\s]+)/i', $agent, $regs)) {
        $browser = 'Safari';
        $browser_ver = $regs[1];
    }
    if (preg_match('/MSIE\s([^\s|;]+)/i', $agent, $regs)) {
        $browser = 'Internet Explorer';
        $browser_ver = $regs[1];
    }
    if (preg_match('/Opera[\s|\/]([^\s]+)/i', $agent, $regs)) {
        $browser = 'Opera';
        $browser_ver = $regs[1];
    }
    if (preg_match('/NetCaptor\s([^\s|;]+)/i', $agent, $regs)) {
        $browser = '(Internet Explorer ' . $browser_ver . ') NetCaptor';
        $browser_ver = $regs[1];
    }
    if (preg_match('/Maxthon/i', $agent, $regs)) {
        $browser = '(Internet Explorer ' . $browser_ver . ') Maxthon';
        $browser_ver = '';
    }
    if (preg_match('/360SE/i', $agent, $regs)) {
        $browser = '(Internet Explorer ' . $browser_ver . ') 360SE';
        $browser_ver = '';
    }
    if (preg_match('/SE 2.x/i', $agent, $regs)) {
        $browser = '(Internet Explorer ' . $browser_ver . ') 搜狗';
        $browser_ver = '';
    }
    if (preg_match('/FireFox\/([^\s]+)/i', $agent, $regs)) {
        $browser = 'FireFox';
        $browser_ver = $regs[1];
    }
    if (preg_match('/Lynx\/([^\s]+)/i', $agent, $regs)) {
        $browser = 'Lynx';
        $browser_ver = $regs[1];
    }
    if (preg_match('/Chrome\/([^\s]+)/i', $agent, $regs)) {
        $browser = 'Chrome';
        $browser_ver = $regs[1];
    }
    if (preg_match('/MicroMessenger\/([^\s]+)/i', $agent, $regs)) {
        $browser = '微信浏览器';
        $browser_ver = $regs[1];
    }
    if ($browser != '') {
        return ['browser' => $browser, 'browser_ver' => $browser_ver];
    } else {
        return ['browser' => '未知', 'browser_ver' => ''];
    }
}

/**
 * 检查是否是合法的IP
 */
function isIp($ip)
{
    if (preg_match('/^((\d|[1-9]\d|2[0-4]\d|25[0-5]|1\d\d)(?:\.(\d|[1-9]\d|2[0-4]\d|25[0-5]|1\d\d)){3})$/', $ip)) {
        return true;
    } else {
        return false;
    }
}

/**
 * 验证手机号
 */
function isMobile($mobile)
{
    return preg_match('/^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|166|198|199|(147))\d{8}$/i', $mobile);
}

/**
 * 判断是否是一个链接
 * @param $url
 * @return bool
 */
function isUrl($url)
{
    if (!empty($url)) {
        $preg = "/^http(s)?:\\/\\/.+/";
        if (preg_match($preg, $url)) {
            return true;
        }
    }
    return false;
}

