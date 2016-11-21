/**
 * CLS_AJAX
 */
if(typeof(CLS_AJAX) == 'undefined'){
var CLS_AJAX = {
    name    : 'CLS_AJAX',
    version : '1.0',
    _url    : null,
    _call   : null,
    _call_er: null,
    _call_bf: null,
    _data   : {},
    _dtype  : 'json',
    _type   : 'POST',
    /**
     * 设置URL
     * @date   2015-12-07
     * @param  string   _url URL
     */
    set_url: function(_url) {
        this._url = _url;
        return this;
    },
    /**
     * 设置回调函数
     * @date   2015-12-07
     * @param  string   _url URL
     */
    set_call: function(_call) {
        this._call = _call;
        return this;
    },
    /**
     * 设置错误回调函数
     * @date   2015-12-07
     * @param  string   _call_er URL
     */
    set_call_er: function(_call_er) {
        this._call_er = _call_er;
        return this;
    },
    /**
     * 设置发送请求前回调函数
     * @date   2015-12-07
     * @param  string   _call_er URL
     */
    set_call_bf: function(_call_bf) {
        this._call_bf = _call_bf;
        return this;
    },
    /**
     * 清除参数
     * @date   2015-12-07
     */
    clear_data: function() {
        this._data = {};
        return this;
    },
    /**
     * 设置参数
     * @date   2015-12-07
     * @param  Object   _data
     */
    set_data: function(_data) {
        this._data = _data;
        return this;
    },
    /**
     * 添加参数
     * @date   2015-12-07
     * @param  string   _k  key
     * @param  string   _v  value
     */
    add_data: function(_k, _v) {
        this._data[_k] = _v;
        return this;
    },
    /**
     * 删除参数
     * @date   2015-12-07
     * @param  string   _k key
     */
    del_data: function(_k) {
        for(var a in this._data) {
            if(a == _k){
                delete this._data[a];
            }
        }
        return this;
    },
    /**
     * 设置数据传递方法
     * @date   2015-12-07
     * @param  string   _dtype
     */
    set_dtype: function(_dtype) {
        this._dtype = _dtype;
        return this;
    },
    /**
     * 设置数据类型
     * @date   2015-12-07
     * @param  string   _type
     */
    set_type: function(_type) {
        this._type = _type;
        return this;
    },
    /**
     * 重置参数
     * @date   2015-12-07
     */
    reset: function() {
        this._url = null;
        this._call = null;
        this._call_er = null;
        this._call_bf = null;
        this._data = {};
        this._dtype = 'json';
        this._type = 'POST';
        return this; 
    },
    /**
     * 发送请求
     * @date   2015-12-07
     */
    send: function() {
        var _this = this;
        if($.inArray((_this._dtype).toLowerCase(), ['xml', 'html', 'script', 'json', 'jsonp', 'text'])) {
            _this._dtype = 'json';
        }
        if((_this._type).toUpperCase() != 'GET') {
            _this._type = 'POST';
        }
        $.ajax({
            url  : _this._url,
            data : _this._data,
            type : _this._type,
            cache: false,
            dataType: _this._dtype,
            success: function(_res) {
                if(typeof(_this._call)=='function') {
                    _this._call.call(_this, _res);
                }
            },
            error: function(_res) {
                if(typeof(_this._call_er)=='function') {
                    _this._call_er.call(_this, _res);
                }
            },
            beforeSend: function(_res) {
                if(typeof(_this._call_bf)=='function') {
                    _this._call_bf.call(_this, _res);
                }
            }
        });
        return _this;
    },
};
}