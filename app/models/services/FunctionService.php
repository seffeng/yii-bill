<?php

namespace zxf\models\services;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;

/**
 * 函数
 * @author ZhangXueFeng
 * @date   2016年11月3日
 */
class FunctionService {

    /**
     * 检测是否允许foreach
     * @author ZhangXueFeng
     * @date   2016年11月3日
     * @param unknown $varName
     * @return boolean
     */
    public static function isForeach($varName){
        if(is_array($varName) && count($varName) > 0) return TRUE;
        if(is_object($varName)) return TRUE;
        return FALSE;
    }

    /**
     * 返回错误
     * @author ZhangXueFeng
     * @date   2016年11月3日
     * @param  mixed $model
     * @param  boolean $isOne 是否返回单个错误
     * @return string
     */
    public static function getErrorsForString($model, $isOne=TRUE) {
        $errors = $model->getErrors();
        $return = '';
        if (self::isForeach($errors)) {
            foreach ($errors as $error) {
                $return .= ArrayHelper::getValue($error, '0');
                if ($isOne) {
                    return $return;
                }
                $return .= ' ';
            }
        }
        return $return;
    }

    /**
     * 检测变量是否为空
     * @author ZhangXueFeng
     * @date   2016年11月3日
     * @param  mixed $name 需要判断变量
     * @return boolean
     */
    public static function isEmpty($name){
        $return = FALSE;
        !isset($name) && $return = TRUE;
        if(!$return){
            switch(strtolower(gettype($name))){
                case 'null'     : { $return = TRUE; break; }
                case 'integer'  : { $return = FALSE; break; }
                case 'double'   : { $return = FALSE; break; }
                case 'boolean'  : { $return = FALSE; break; }
                case 'string'   : { $return = $name === '' ? TRUE : FALSE; break; }
                case 'array'    : { $return = count($name) > 0 ? FALSE : TRUE; break; }
                case 'object'   : { $return = $name === NULL ? TRUE : FALSE; break; }
                case 'resource' : { $return = $name === NULL ? TRUE : FALSE; break; }
                default : { $return = TRUE; }
            }
        }
        return $return;
    }

    /**
     * ip地址转成长整型
     * @author ZhangXueFeng
     * @date   2016年11月3日
     * @param  string $ip
     * @return integer
     */
    public static function ip2long($ip) {
        $return = 0;
        $tmp_array = explode('.', $ip);
        foreach ($tmp_array as $key => $val) {
            $return += intval($val) * pow(256, abs($key - 3));
        }
        return $return;
    }

    /**
     * 长整型转成ip地址
     * @author ZhangXueFeng
     * @date   2016年11月3日
     * @param  integer $longIp
     * @return string
     */
    public static function long2ip($longIp) {
        if($longIp < 0 || $longIp > 4294967295) return FALSE;
        $ip = '';
        for ($i=3; $i>=0; $i--) {
            $tmpNum = intval($longIp / pow(256, $i));
            $ip .= $tmpNum;
            $longIp -= $tmpNum * pow(256, $i);
            if($i > 0) $ip .= '.';
        }
        return $ip;
    }

    /**
     * 返回错误提示或页面
     * @author ZhangXueFeng
     * @date   2016年11月9日
     * @param  array $return
     * @throws HttpException
     * @return array
     */
    public static function returnError($return) {
        if (Yii::$app->request->isAjax) {
            return $return;
        } else {
            throw new HttpException(500, $return['m'], $return['d'] ?? 500);
        }
    }

    /**
     * 获取客户端IP
     * @author Save.zxf
     * @date   2016年11月10日
     * @return string
     */
    public static function getUserIP() {
        $ip = '';
        isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        if(!self::isEmpty($ip)) return $ip;
        isset($_SERVER['HTTP_CLIENT_IP']) && $ip = $_SERVER['HTTP_CLIENT_IP'];
        if(!self::isEmpty($ip)) return $ip;
        isset($_SERVER['REMOTE_ADDR']) && $ip = $_SERVER['REMOTE_ADDR'];
        if(!self::isEmpty($ip)) return $ip;
        $ip = getenv('HTTP_X_FORWARDED_FOR');
        if(!self::isEmpty($ip)) return $ip;
        $ip = getenv('HTTP_CLIENT_IP');
        if(!self::isEmpty($ip)) return $ip;
        $ip = getenv('REMOTE_ADDR');
        if(!self::isEmpty($ip)) return $ip;
        return '0.0.0.0';
    }

    /**
     * 检测数据规则
     * @author ZhangXueFeng
     * @date   2016年11月11日
     * @param  string $string  被检测的原字符串
     * @param  string $type    检测的类型
     * @return boolean
     */
    public static function checkData($string, $type='email'){
        $return = FALSE;
        switch($type){
            case 'email'        : { $return = preg_match("/^(\w+[-+.]*\w+)*@(\w+([-.]*\w+)*\.\w+([-.]*\w+)*)$/", $string); break; }
            case 'http'         : { $return = preg_match("/^http:\/\/[A-Za-z0-9-]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"])*$/", $string); break; }
            case 'qq'           : { $return = preg_match("/^[1-9]\d{4,11}$/", $string); break; }
            case 'post'         : { $return = preg_match("/^[1-9]\d{5}$/", $string); break; }
            case 'idnum'        : { $return = preg_match("/^\d{15}(\d{2}[A-Za-z0-9])?$/", $string); break; }
            case 'china'        : { $return = preg_match("/^[".chr(0xa1)."-".chr(0xff)."]+$/", $string); break; } //GBK中文
            case 'english'      : { $return = preg_match("/^[A-Za-z]+$/", $string); break; }
            case 'mobile'       : { $return = preg_match("/^((\(\d{3}\))|(\d{3}\-))?((13)|(14)|(15)|(17)|(18)){1}\d{9}$/", $string); break; }
            case 'phone'        : { $return = preg_match("/^((\(\d{3}\))|(\d{3}\-))?(\(0\d{2,3}\)|0\d{2,3}-)?[1-9]\d{6,7}$/", $string); break; }
            case 'safe'         : { $return = preg_match("/^(([A-Z]*|[a-z]*|\d*|[-_\~!@#\$%\^&\*\.\(\)\[\]\{\}<>\?\\\/\'\"]*)|.{0,5})$|\s/", $string) != 0 ? TRUE : FALSE; break; }
            case 'age'          : { $return = (preg_match("/^(-{0,1}|\+{0,1})[0-9]+(\.{0,1}[0-9]+)$/", $string) && intval($string) <= 130 && intval($string) >= 12) ? TRUE : FALSE; break; }
            case 'eng_num'      : { $return = preg_match("/^[A-Za-z0-9]+$/", $string); break; }
            case 'password'     : { $return = (preg_match("/^[A-Za-z0-9]+$/", $string) && strlen($string) <= 32 && strlen($string) >= 6) ? TRUE : FALSE; break; }
            case 'datetime'     : { $return = preg_match('/^[\d]{4}-[\d]{1,2}-[\d]{1,2}\s[\d]{1,2}:[\d]{1,2}:[\d]{1,2}$/', $string); break; }
            case 'datetimes'    : { $return = preg_match('/^[\d]{4}-[\d]{2}-[\d]{2}\s[\d]{2}:[\d]{2}:[\d]{2}$/', $string); break; }
            case 'date'         : { $return = preg_match('/^[\d]{4}-[\d]{1,2}-[\d]{1,2}$/', $string); break; }
            case 'dates'        : { $return = preg_match('/^[\d]{4}-[\d]{2}-[\d]{2}$/', $string); break; }
            case 'time'         : { $return = preg_match('/^[\d]{1,2}:[\d]{1,2}:[\d]{1,2}$/', $string); break; }
            case 'times'        : { $return = preg_match('/^[\d]{2}:[\d]{2}:[\d]{2}$/', $string); break; }
            case 'ip'           : { $return = preg_match("/^\b(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\b$/", $string); break; }
            case 'incchinese'   : { $return = preg_match('/[\x{4e00}-\x{9fa5}]+/u', $string); break; } //是否包含中文
            case 'plusnum'      : { $return = preg_match('/^[1-9]*[1-9][0-9]*$/', $string); break; } //是否是正整数
            case 'num'          : { $return = preg_match('/^[1-9]*[1-9][0-9]*[\.]?[\d]{0,2}$/', $string); break; } //是否是数字
            case 'hostrecord'   : { $return = preg_match('/^[A-Z_a-z0-9][A-Za-z0-9-]+(\.[A-Za-z0-9-_]+)*$/', $string); break; } //正确的主机记录,english
            case 'cnhostrecord' : { $return = preg_match('/^[_a-zA-Z0-9]*([\x{4e00}-\x{9fa5}]*[-a-zA-Z0-9\.]*)+[a-zA-Z0-9_]$/iu', $string); break; } //正确的主机记录,english chinese
            case 'domain'       : { $return = preg_match('/^[A-Za-z0-9][A-Za-z0-9-]+(\.[A-Za-z0-9-]+){1,3}$/', $string); break; } //是否是域名
            case 'cndomain'     : { $return = (preg_match('/[\x{4e00}-\x{9fa5}]+/u', $string) && preg_match('/^([-a-zA-Z0-9\.]*[\x{4e00}-\x{9fa5}]*[-a-zA-Z0-9\.]*)+\.(中国|公司|网络|CN|COM|NET)$/iu', $string)) ? TRUE : FALSE; break; }  //是否中文域名
            case 'mac'          : { $return = preg_match('/^[a-fA-F\d]{2}:[a-fA-F\d]{2}:[a-fA-F\d]{2}:[a-fA-F\d]{2}:[a-fA-F\d]{2}:[a-fA-F\d]{2}$/', $string); break; }
            case 'ipv6'         : { $return = preg_match('/^\s*((([0-9A-Fa-f]{1,4}:){7}([0-9A-Fa-f]{1,4}|:))|(([0-9A-Fa-f]{1,4}:){6}(:[0-9A-Fa-f]{1,4}|((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){5}(((:[0-9A-Fa-f]{1,4}){1,2})|:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){4}(((:[0-9A-Fa-f]{1,4}){1,3})|((:[0-9A-Fa-f]{1,4})?:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){3}(((:[0-9A-Fa-f]{1,4}){1,4})|((:[0-9A-Fa-f]{1,4}){0,2}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){2}(((:[0-9A-Fa-f]{1,4}){1,5})|((:[0-9A-Fa-f]{1,4}){0,3}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){1}(((:[0-9A-Fa-f]{1,4}){1,6})|((:[0-9A-Fa-f]{1,4}){0,4}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(:(((:[0-9A-Fa-f]{1,4}){1,7})|((:[0-9A-Fa-f]{1,4}){0,5}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:)))(%.+)?\s*$/', $string); break; }
        }
        gettype($return) == 'integer' && $return = $return == 0 ? FALSE : TRUE;
        return $return;
    }

    /**
     * 过滤非安全字符
     * @author ZhangXueFeng
     * @date   2016年11月11日
     * @param  string $string 被过滤的原字符串或数组
     * @return string
     */
    public static function filterString($string){
        if(self::isEmpty($string)) return '';
        if(is_array($string)){
            foreach($string as $key => $val) $string[$key] = self::filterString($val);
            return $string;
        }else{
            $string = preg_replace_callback("'<script[^>]*?>.*?</script>'si", function($match){ return ''; }, $string);
            $string = preg_replace_callback("'<[\/\!]*?[^<>]*?>'si", function($match){ return ''; }, $string);
            $string = preg_replace_callback("'([\r\n])[\s]+'", function($match){ return $match[1]; }, $string);
            $string = preg_replace_callback("'&(quot|#34);'i", function($match){ return '"'; }, $string);
            $string = preg_replace_callback("'&(amp|#38);'i", function($match){ return '&'; }, $string);
            $string = preg_replace_callback("'&(lt|#60);'i", function($match){ return '<'; }, $string);
            $string = preg_replace_callback("'&(gt|#62);'i", function($match){ return '>'; }, $string);
            $string = preg_replace_callback("'&(nbsp|#160);'i", function($match){ return ' '; }, $string);
            $string = preg_replace_callback("'&(iexcl|#161);'i", function($match){ return chr(161); }, $string);
            $string = preg_replace_callback("'&(cent|#162);'i", function($match){ return chr(162); }, $string);
            $string = preg_replace_callback("'&(pound|#163);'i", function($match){ return chr(163); }, $string);
            $string = preg_replace_callback("'&(copy|#169);'i", function($match){ return chr(169); }, $string);
            $string = preg_replace_callback("'&#(\d+);'", function($match){ return chr($match[1]); }, $string);
            return trim(addslashes(nl2br(stripslashes($string))));
        }
    }

    /**
     * 返回差异数据
     * @author Save.zxf
     * @date   2016年11月13日
     * @param  mixed $model AceiveRecord对象
     * @param  array $diffAttr 需记录的表字段
     */
    public static function modelDiff($model, $diffAttr) {
        $return = [];
        if ($diffAttr) foreach ($diffAttr as $val) {
            $value    = $model->getAttribute($val);
            $oldValue = $model->getOldAttribute($val);
            if ($oldValue != $value) {
                $return[$val][] = $oldValue;
                $return[$val][] = $value;
            }
        }
        return $return;
    }

    /**
     * 截取字符串
     * @author ZhangXueFeng
     * @date   2016年11月17日
     * @param: string 字符串
     * @param: integer 位数
     * @return string
     */
    public static function subString($name=NULL, $length=NULL){
        $contactName = trim($name);
        if(strlen($contactName) <= $length){
            return $contactName;
        }else{
            $return = '';
            $i = 0;
            for(;$i < $length; $i++){
                $tmpString = substr($contactName, $i, 1);
                if(ord($tmpString) > 0xA0){    /* UTF-8中文字符占三个字节 */
                    $return .= substr($contactName, $i, 3);
                    $i += 2;
                }else{
                    $return .= $tmpString;
                }
            }
            return $return.'...';
        }
    }
}