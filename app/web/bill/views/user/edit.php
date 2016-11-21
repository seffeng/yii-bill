<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use zxf\models\entities\User;

$this->params['breadcrumb'] = isset($breadcrumb) ? $breadcrumb : [];
?>
<div class="box-primary">
    <div class="box-header"></div>
        <?php $form = ActiveForm::begin([
            'id'    => 'add-form',
            'options' => ['class' => 'form-horizontal box-body'],
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-lg-4\">{input}</div>\n<div class=\"col-lg-6\">{error}</div>",
                'labelOptions' => ['class' => 'col-lg-2 control-label'],
            ],
        ]); ?>
        <?php
        echo $form->field($model, 'u_username');
        echo $form->field($model, 'u_password')->passwordInput(['value' => '']);
        ?>
        <hr />
        <?php
        echo $form->field($userInfo, 'ui_name');
        echo $form->field($userInfo, 'ui_phone', ['inputOptions' => ['class' => 'form-control', 'value' => $userInfo->ui_phone ?: '']]);
        echo $form->field($userInfo, 'ui_email');
        ?>
        <div class="form-group">
            <div class="col-lg-offset-3 col-lg-4">
                <?php echo Html::button('确&nbsp;&nbsp;定', ['adm' => 'submit', 'class' => 'btn btn-primary', 'data-loading-text' => 'Loading...']); ?>
            </div>
        </div>
        <?php $form->end(); ?>
    <div class="box-footer"></div>
</div>
<script>
$(document).ready(function(){
    /* 初始化 */
    CLS_FORM.init({url: "<?php echo Url::to(['user/edit']); ?>", url_edit: "<?php echo Url::to(['user/edit']); ?>"});

    /* 状态 */
    $('input[name="User[u_status]"]').bootstrapSwitch({onText: '启用', offText: '停用', onColor: 'success', offColor: 'warning'});

    /**
     * 修改
     * @date   2016-11-7
     */
    $('button[adm="submit"]').on('click', function(){
        var _u_username = $('#user-u_username').val();
        var _u_password = $('#user-u_password').val();
        var _ui_name  = $('#userinfo-ui_name').val();
        var _ui_phone = $('#userinfo-ui_phone').val();
        var _ui_email = $('#userinfo-ui_email').val();
        if (!checkForm()) {
            return false;
        }
        var _data = {'User[u_username]': _u_username, 'User[u_password]': _u_password, 'UserInfo[ui_name]': _ui_name, 'UserInfo[ui_phone]': _ui_phone, 'UserInfo[ui_email]': _ui_email};
        CLS_FORM.submit(CLS_FORM._url_edit, _data);
    });

    /* input失去焦点检测 */
    $('#add-form input').on('blur', function(){
        checkForm();
    });
});

/**
 * 输入数据检查
 */
function checkForm() {
    var _u_username = $('#user-u_username').val();
    var _ui_name = $('#userinfo-ui_name').val();
    if (_u_username == '') {
        $('.field-user-u_username').removeClass('has-success').addClass('has-error').find('.help-block').text('用户名 不能为空！');
        return false;
    }
    $('.field-user-u_username').removeClass('has-error').addClass('has-success').find('.help-block').text('');
    if (_ui_name == '') {
        $('.field-userinfo-ui_name').removeClass('has-success').addClass('has-error').find('.help-block').text('姓名 不能为空！');
        return false;
    }
    $('.field-userinfo-ui_name').removeClass('has-error').addClass('has-success').find('.help-block').text('');
    return true;
}
</script>