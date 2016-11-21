/* 登录js类 */

if(typeof(CLS_LOGIN) == 'undefined'){
var CLS_LOGIN = {
    name    : 'CLS_LOGIN',
    version : '1.0',
    user_id : '',       /* 用户名 文本框ID */
    pass_id : '',       /* 密码 文本框ID */
    btn_id  : '',       /* 登录按钮ID */
    user_name : '',     /* 用户名 */
    user_pass : '',     /* 密码 */
    login_url : '',     /* 登录URL */
    /**
     * 初始化
     * @param: Object _obj
     */
    init : function(_obj) {
        if (typeof(_obj.user_id) != 'undefined') this.user_id = _obj.user_id;
        if (typeof(_obj.pass_id) != 'undefined') this.pass_id = _obj.pass_id;
        if (typeof(_obj.btn_id)  != 'undefined') this.btn_id  = _obj.btn_id;
        if (typeof(_obj.login_url) != 'undefined') this.login_url  = _obj.login_url;
        this.btn_init();
        this.enter_event();
    },
    /* 登录 */
    login : function() {
        var _data = {username: this.user_name, userpass: this.user_pass};
        CLS_AJAX.reset().set_url(this.login_url).set_data(_data).set_call(function(_res){
            if (_res.r == 1) {
                CLS_ALERT.show({text: _res.m, type: 'success'});
                window.location.href = _res.u;
                return false;
            } else {
                CLS_ALERT.show(_res.m);
            }
            CLS_ALERT.unload();
        }).set_call_er(function(_res){
            CLS_ALERT.show('异常错误');
            CLS_ALERT.unload();
        }).set_call_bf(function(){
            CLS_ALERT.loading();
        }).send();
    },
    /* 用户名,密码检查 */
    check : function() {
        if (this.user_name == '') {
            CLS_ALERT.show('请输入用户名');
            return false;
        }
        if (this.user_pass == '') {
            CLS_ALERT.show('请输入密码');
            return false;
        }
        return true;
    },
    /* 登录按钮事件初始化 */
    btn_init : function() {
        if (this.btn_id != '') {
            $('#'+ this.btn_id).on('click', function(){
                /* var this_btn = $(this).button('loading'); */
                CLS_LOGIN.user_name = $('#'+ CLS_LOGIN.user_id).val();
                CLS_LOGIN.user_pass = $('#'+ CLS_LOGIN.pass_id).val();
                if (CLS_LOGIN.check()) {
                    CLS_LOGIN.login();
                }
                /* this_btn.button('reset'); */
            });
        } else {
            CLS_ALERT.show('异常错误');
        }
    },
    /* 回车事件 */
    enter_event: function() {
        $('input').on('keypress', function(e){
            if (e.keyCode == 13) {
                $('#'+ CLS_LOGIN.btn_id).click();
            }
        });
    }
}
}