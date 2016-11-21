/**
 * 导航类
 */
if(typeof(CLS_MENU) == 'undefined'){
var CLS_MENU = {
    name    : 'CLS_MENU',
    version : '1.0',
    ul_id   : '#sidebar-menu',
    nav     : null,
    type    : 'GET',
    dtype   : 'json',
    data    : {},
    /* 初始化 */
    init    : function(_nav) {
        this.nav = _nav;
        this.set_sidebar();
    },
    /* 设置导航菜单 */
    set_sidebar : function() {
        var _k = -1;
        if(typeof(this.nav) != 'undefined'){
            $(this.ul_id).empty();
            var _html = '<li class="header">MAIN NAVIGATION</li>';
            for(var i in this.nav){
                if(_k < 1) _k = this.nav[i].mn_id;
                if(typeof(this.nav[i].list) == 'undefined'){
                    _html += '<li mn_id="'+this.nav[i].mn_id+'"><a href="javascript:;" onclick="javascript:CLS_MENU.reset().set_url(\''+ this.nav[i].mn_url+ '\', '+ this.nav[i].mn_id +');"><i class="fa '+ this.nav[i].mn_icon +'"></i> <span>'+ this.nav[i].mn_name +'</span></a></li>';
                }else{
                    _html += '<li class="treeview" mn_id="'+ this.nav[i].mn_id +'"><a href="javascript:;"><i class="fa '+ this.nav[i].mn_icon +'"></i> <span>'+ this.nav[i].mn_name +'</span> <i class="fa fa-angle-left pull-right"></i></a><ul class="treeview-menu">';
                    for(var j in this.nav[i].list) {
                        _html += '<li mn_id="'+ this.nav[i].list[j].mn_id +'"><a href="javascript:;" onclick="javascript:CLS_MENU.reset().set_url(\''+ this.nav[i].list[j].mn_url+ '\', '+ this.nav[i].list[j].mn_id +');"><i class="fa '+ this.nav[i].list[j].mn_icon +'"></i> '+ this.nav[i].list[j].mn_name +'</a></li>';
                    }
                    _html += '</ul></li>';
                }
            }
            $(this.ul_id).html(_html);
        }
    },
    /**
     * 设置链接
     * @date   2015-12-11
     * @author ZhangXueFeng
     * @param  string   _url  链接地址
     * @param  integer  _mnid 导航ID
     */
    set_url: function(_url,_mnid){
        if(typeof(_url) == 'undefined' || _url == ''){
            CLS_ALERT.show({text: "操作无效!",timeout: 3000});
            return;
        }
        $('li[mn_id="'+ _mnid +'"]').addClass('active').siblings().removeClass('active');
        CLS_MENU.to_url(_url);
    },
    /**
     * 访问链接
     * @date   2015-12-11
     * @author ZhangXueFeng
     * @param  string   _url  链接地址
     */
    to_url: function(_url) {
        var _data = (typeof(this.data) == 'object' || typeof(this.data) == 'Object') ? this.data : {};
        CLS_AJAX.reset().set_url(_url).
        set_data(_data).
        set_type(this.type).
        set_dtype(this.dtype).
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
            }
        }).
        set_call_bf(function(_res){CLS_ALERT.loading();}).
        set_call_er(function(_res){
            CLS_ALERT.unload();
            CLS_ALERT.show({text:(typeof(_res.m) != 'undefined' ? _res.m:"操作异常!"),timeout: 3000});
        }).send();
    },
    /**
     * 设置数据传递方式
     * @date   2015-12-11
     * @author ZhangXueFeng
     * @param  string   _type 传递类型[POST|GET...]
     */
    set_type : function(_type) {
        if(typeof(_type) != 'undefined') this.type = _type;
        return this;
    },
    /**
     * 设置数据类型
     * @date   2015-12-11
     * @author ZhangXueFeng
     * @param  string   _dtype 数据类型类型[json|text|xml...]
     */
    set_dtype : function(_dtype) {
        if(typeof(_dtype) != 'undefined') this.dtype = _dtype;
        return this;
    },
    /**
     * 设置数据
     * @date   2015-12-11
     * @author ZhangXueFeng
     * @param  object   _data 数据
     */
    set_data : function(_data) {
        if(typeof(_data) != 'undefined') this.data = _data;
        return this;
    },
    /* 重置数据 */
    reset    : function() {
        this.data = {};
        this.dtype = 'json';
        this.type = 'GET';
        return this;
    }
}
}