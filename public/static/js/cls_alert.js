/* 提示js类  */
if(typeof(CLS_ALERT) == 'undefined'){
var CLS_ALERT = {
    name    : 'CLS_ALERT',
    version : '1.0',
    box_id  : '.alert-box',     /* 提示域ID */
    modal_id: '#main_modal',
    modal_title : '#modal_title',
    modal_body  : '#modal_body',
    in_type : ['info', 'warning', 'danger', 'success'],     /* 提示类型 */
    data    : {type: 'warning', text: '', timeout: 3000}, /* 参数 */
    /**
     * 设置数据
     * @param: Object _obj
     */
    set_data : function(_obj) {
        this.reset();
        if(typeof(_obj) == 'object' || typeof(_obj) == 'Object') {
            if(typeof(_obj.type) != 'undefined' && $.inArray(_obj.type, this.in_type)) this.data.type = _obj.type;
            if(typeof(_obj.timeout) != 'undefined') this.data.timeout = _obj.timeout;
            if(typeof(_obj.text) != 'undefined') this.data.text = _obj.text;
        }else if(typeof(_obj) == 'string' || typeof(_obj) == 'String') {
            this.data.text = _obj;
        } else {
            this.data.text = '参数错误！';
        }
    },
    /* 重置数据 */
    reset: function() {
        this.data = {type: 'warning', 'text': '', timeout: 3000};
    },
    /**
     * 显示提示
     * @param: Object _obj
     */
    show : function(_obj) {
        this.set_data(_obj);
        var rand_id = Math.random();
        $(this.box_id).append('<div class="alert alert-'+ this.data.type +' alert-dismissible" rand="'+ rand_id +'"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'+ this.data.text +'</div>');
        setTimeout("CLS_ALERT.hide(\'"+ rand_id +"\')", this.data.timeout);
    },
    /**
     * 删除提示
     * @param: float rand_id 提示DIV识别随机数
     */
    hide : function(rand_id) {
        $(this.box_id +' .alert[rand="'+ rand_id +'"]').slideUp('normal', function(){ $(CLS_ALERT.box_id +' .alert[rand="'+ rand_id +'"]').remove(); });
    },
    /* 显示loading样式 */
    loading : function() {
        $('#main_content').append('<div class="ajax-loading-overlay"><i class="ajax-loading-icon fa fa-spin fa-spinner fa-2x orange"></i> </div>');
    },
    /* 删除loading样式 */
    unload : function() {
        $('div.ajax-loading-overlay').remove();
    },
    /* 显示模态框提示 */
    show_modal : function(_title, _html) {
        if(typeof(_title) != 'undefined') $(this.modal_title).text(_title);
        if(typeof(_html) != 'undefined') $(this.modal_body).html(_html);
        $(this.modal_id).modal('show');
    },
    /* 隐藏模态框 */
    hide_modal : function() {
        $(this.modal_id).modal('hide');
    }
}
}