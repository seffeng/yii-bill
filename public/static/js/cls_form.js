/**
 * CLS_FORM
 */
if(typeof(CLS_FORM) == 'undefined'){
var CLS_FORM = {
    name    : 'CLS_FORM',
    version : '1.0',
    _data   : {},
    _url    : '',
    _url_add: '',
    _url_del: '',
    _url_edit : '',
    /* 初始化 */
    init    : function(_obj) {
        if(typeof(_obj.url) != 'undefined') this._url = _obj.url;
        if(typeof(_obj.url_add) != 'undefined') this._url_add = _obj.url_add;
        if(typeof(_obj.url_del) != 'undefined') this._url_del = _obj.url_del;
        if(typeof(_obj.url_edit) != 'undefined') this._url_edit = _obj.url_edit;
        this.list();
        this.add();
        this.edit();
        this.del();
        this.sort();
        this.del_ok();
        this.key_enter();
        this.is_checked();
    },
    /* 列表 */
    list    : function() {
        $('span[adm="list"]').off().on('click', function(){
            CLS_MENU.reset().to_url(CLS_FORM._url);
        });
    },
    /* 添加 */
    add     : function() {
        $('span[adm="add"]').off().on('click', function(){
            CLS_MENU.reset().to_url(CLS_FORM._url_add);
        });
    },
    /* 编辑 */
    edit    : function() {
        $('span[adm="edit"]').off().on('click', function(){
            var _id = $(this).attr('data');
            if(_id < 1) {
                CLS_ALERT('ID 错误');
                return false;
            }
            CLS_MENU.reset().set_data({id: _id}).to_url(CLS_FORM._url_edit);
        });
    },
    /* 删除 */
    del     : function() {
        $('span[adm="del"]').off().on('click', function(){
            var _id = $(this).attr('data');
            if (_id < 0) {
                CLS_ALERT.show('ID 错误');
                return false;
            }
            CLS_FORM._data = {id: _id};
            CLS_ALERT.show_modal('删除提醒', '确定要删除？');
        });
    },
    /* 确定删除 */
    del_ok  : function() {
        $('#dialog_ok').off().on('click', function(){
            if (CLS_FORM._data.id > 0) {
                CLS_AJAX.reset().set_url(CLS_FORM._url_del).set_data(CLS_FORM._data).set_call(function(_res){
                    CLS_ALERT.hide_modal();
                    if (_res.r == 1) {
                        CLS_FORM.reset_data();
                        CLS_ALERT.show({text: _res.m, type: 'success'});
                        CLS_MENU.reset().to_url(CLS_FORM._url);
                        return false;
                    } else {
                        CLS_ALERT.show(_res.m);
                    }
                    CLS_ALERT.unload();
                }).set_call_er(function(_res){
                    CLS_ALERT.hide_modal();
                    CLS_ALERT.show('异常错误');
                    CLS_ALERT.unload();
                }).set_call_bf(function(){
                    CLS_ALERT.loading();
                }).send();
            } else {
                CLS_ALERT.show('ID 错误');
            }
        });
    },
    /**
     * 添加/编辑操作
     * @date   2015-12-27
     * @author ZhangXueFeng
     * @param  string   _url  处理地址
     * @param  Object   _data 数据
     */
    submit  : function(_url, _data) {
        CLS_AJAX.reset().set_url(_url).
        set_data(_data).
        set_call(function(_res){
            CLS_ALERT.unload();
            if(typeof(_res.r) != 'undefined' && _res.r == 0){
                CLS_ALERT.show({text: (typeof(_res.m) != 'undefined' ? _res.m : "操作异常!"), timeout: (typeof(_res.t) != 'undefined' ? _res.t : 3000)});
            }else{
                if(typeof(_res.d) != 'undefined') {
                    if(typeof(_res.d.breadcrumb) != 'undefined') {
                        if(typeof(_res.d.breadcrumb[0])) $('#breadcrumb_h').html(_res.d.breadcrumb[0]);
                        if(typeof(_res.d.breadcrumb[1])) $('#breadcrumb_a').html(_res.d.breadcrumb[1]);
                        if(typeof(_res.d.breadcrumb[2])) $('#breadcrumb_li').html(_res.d.breadcrumb[2]);
                    }
                    if(typeof(_res.d.content) != 'undefined') $('#main_content').html(_res.d.content);
                }
                if(typeof(_res.m) != 'undefined' && _res.m != '') CLS_ALERT.show({text: _res.m, type: 'success'});
                if(typeof(_res.r) != 'undefined' && _res.r == 1) CLS_MENU.reset().to_url(CLS_FORM._url);
            }
        }).
        set_call_bf(function(_res){CLS_ALERT.loading();}).
        set_call_er(function(_res){
            CLS_ALERT.unload();
            CLS_ALERT.show({text:(typeof(_res.m) != 'undefined' ? _res.m:"操作异常!"),timeout: 3000});
        }).send();
    },
    /* 排序 */
    sort    : function() {
        $('a[data-sort]').off().on('click', function() {
            var _sort = $(this).attr('data-sort');
            var _href = $(this).attr('href');
            var _href_arr = _href.split('?');
            var _url = typeof(_href_arr[0]) != 'undefined' ? _href_arr[0] : '';
            var _query_arr = typeof(_href_arr[1]) != 'undefined' ? _href_arr[1].split('&') : false;
            var _data = {};
            if(_query_arr) {
                for(var i in _query_arr) {
                    var _tmp = _query_arr[i].split('=');
                    if (typeof(_tmp[0]) != 'undefined' && typeof(_tmp[1]) != 'undefined') _data[decodeURIComponent(_tmp[0])] = _tmp[1];
                }
            }
            CLS_MENU.reset().set_data(_data).to_url(_url);
            return false;
        });
    },
    /* 重置数据 */
    reset_data : function() {
        this._data = {};
        return this;
    },
    /* 回车事件 */
    key_enter : function() {
        $('#main_content input').off().on('keypress', function(e){
            if (e.keyCode == 13) {
                $('button[adm="submit"]').click();
                return false;
            }
        });
    },
    /* checkbox 事件 */
    is_checked: function() {
        $('input[type="checkbox"]:checked').each(function(){
            $(this).parent().addClass('text-blue');
        });
        $('input[type="checkbox"]').off().on('click', function(){
            if ($(this).prop('checked')) {
                $(this).parent().addClass('text-blue');
            } else {
                $(this).parent().removeClass('text-blue');
            }
        });
    }
}
}