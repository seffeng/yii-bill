/**
 * 全局函数
**/

if(typeof(CLS_GLOBAL) == 'undefined'){
var CLS_GLOBAL = {
    'name'    : 'CLS_GLOBAL',
    'version' : '1.0',
    'get_id'  : function(_id){
        return document.getElementById(_id);
    },
    'ltrim'   : function(_str){
        return _str.replace(/^[\s]+/, '');
    },
    'rtrim'   : function(_str){
        return _str.replace(/[\s]+$/, '');
    },
    'trim'    : function(_str){
        return this.rtrim(this.ltrim(_str));
    },
    'check_data' : function(_str, _type){
        var _return = false;
        switch(_type){
            case 'email'        : {var _reg = /^(\w+[-+.]*\w+)*@(\w+([-.]*\w+)*\.\w+([-.]*\w+)*)$/; _return = _reg.test(_str); break;}
            case 'username'     : {var _reg = /^[A-Za-z]+[\w\-]*[A-Za-z0-9\-]+$/; _return = _reg.test(_str); break;}
            case 'http'         : {var _reg = /^http:\/\/[A-Za-z0-9-]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"])*$/; _return = _reg.test(_str); break;}
            case 'qq'           : {var _reg = /^[1-9]\d{4,11}$/; _return = _reg.test(_str); break;}
            case 'post'         : {var _reg = /^[1-9]\d{5}$/; _return = _reg.test(_str); break;}
            case 'idnum'        : {var _reg = /^\d{15}(\d{2}[A-Za-z0-9])?$/; _return = _reg.test(_str); break;}
            case 'english'      : {var _reg = /^[A-Za-z]+$/; _return = _reg.test(_str); break;}
            case 'mobile'       : {var _reg = /^((\(\d{3}\))|(\d{3}\-))?((13)|(14)|(15)|(17)|(18)){1}\d{9}$/; _return = _reg.test(_str); break;}
            case 'phone'        : {var _reg = /^((\(\d{3}\))|(\d{3}\-))?(\(0\d{2,3}\)|0\d{2,3}-)?[1-9]\d{6,7}$/; _return = _reg.test(_str); break;}
            case 'safe'         : {var _reg = /^(([A-Z]*|[a-z]*|\d*|[-_\~!@#\$%\^&\*\.\(\)\[\]\{\}<>\?\\\/\'\"]*)|.{0,5})$|\s/; _return = _reg.test(_str); break;}
            case 'age'          : {var _reg = /^(-{0,1}|\+{0,1})[0-9]+(\.{0,1}[0-9]+)$/; _return = (_reg.test(_str) && _str < 150); break;}
            case 'eng_num'      : {var _reg = /^[A-Za-z0-9]+$/; _return = _reg.test(_str); break;}
            case 'password'     : {var _reg = /^[A-Za-z0-9]+$/; var _len = _str.length; _return = (_reg.test(_str) && _len > 5 && _len < 33); break;}
            case 'datetime'     : {var _reg = /^[\d]{4}-[\d]{1,2}-[\d]{1,2}\s[\d]{1,2}:[\d]{1,2}:[\d]{1,2}$/; _return = _reg.test(_str); break;}
            case 'datetimes'    : {var _reg = /^[\d]{4}-[\d]{2}-[\d]{2}\s[\d]{2}:[\d]{2}:[\d]{2}$/; _return = _reg.test(_str); break;}
            case 'date'         : {var _reg = /^[\d]{4}-[\d]{1,2}-[\d]{1,2}$/; _return = _reg.test(_str); break;}
            case 'dates'        : {var _reg = /^[\d]{4}-[\d]{2}-[\d]{2}$/; _return = _reg.test(_str); break;}
            case 'time'         : {var _reg = /^[\d]{1,2}:[\d]{1,2}:[\d]{1,2}$/; _return = _reg.test(_str); break;}
            case 'times'        : {var _reg = /^[\d]{2}:[\d]{2}:[\d]{2}$/; _return = _reg.test(_str); break;}
            case 'ip'           : {var _reg = /^\b(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\b$/; _return = _reg.test(_str); break;}
            case 'incchinese'   : {var _reg = /[\u4e00-\u9fa5]+/; _return = _reg.test(_str); break;}    //是否包含中文
            case 'plusnum'      : {var _reg = /^[1-9]*[1-9][0-9]*$/; _return = _reg.test(_str); break;} //是否是正整数
            case 'num'          : {var _reg = /^[1-9]*[1-9][0-9]*[\.]?[\d]{0,2}$/; _return = _reg.test(_str); break; }  //是否是数字
            case 'hostrecord'   : {var _reg = /^[A-Z_a-z0-9][A-Za-z0-9-]+(\.[A-Za-z0-9-_]+)*$/; _return = _reg.test(_str); break;} //正确的主机记录,english
            case 'cnhostrecord' : {var _reg = /^[_a-zA-Z0-9]*([\u4e00-\u9fa5]*[-a-zA-Z0-9\.]*)+[a-zA-Z0-9_]$/i; _return = _reg.test(_str); break;} //正确的主机记录,english chinese
            case 'domain'       : {var _reg = /^[A-Za-z0-9][A-Za-z0-9-]+(\.[A-Za-z0-9-]+){1,3}$/; _return = _reg.test(_str); break;} //是否是域名
            case 'mac'          : {var _reg = /^[a-fA-F\d]{2}:[a-fA-F\d]{2}:[a-fA-F\d]{2}:[a-fA-F\d]{2}:[a-fA-F\d]{2}:[a-fA-F\d]{2}$/; _return = _reg.test(_str); break;}
            case 'ipv6'         : {var _reg = /^\s*((([0-9A-Fa-f]{1,4}:){7}([0-9A-Fa-f]{1,4}|:))|(([0-9A-Fa-f]{1,4}:){6}(:[0-9A-Fa-f]{1,4}|((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){5}(((:[0-9A-Fa-f]{1,4}){1,2})|:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){4}(((:[0-9A-Fa-f]{1,4}){1,3})|((:[0-9A-Fa-f]{1,4})?:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){3}(((:[0-9A-Fa-f]{1,4}){1,4})|((:[0-9A-Fa-f]{1,4}){0,2}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){2}(((:[0-9A-Fa-f]{1,4}){1,5})|((:[0-9A-Fa-f]{1,4}){0,3}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){1}(((:[0-9A-Fa-f]{1,4}){1,6})|((:[0-9A-Fa-f]{1,4}){0,4}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(:(((:[0-9A-Fa-f]{1,4}){1,7})|((:[0-9A-Fa-f]{1,4}){0,5}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:)))(%.+)?\s*$/; _return = _reg.test(_str); break;}
        }
        return _return;
    }
}
}