<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

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
        echo $form->field($model, 'b_name');
        echo $form->field($model, 'b_type')->dropDownList($typeText);
        echo $form->field($model, 'b_price', ['inputOptions' => ['placeholder' => '支持简单算术，如：(1+1)*4/2', 'class' => 'form-control']]);
        echo $form->field($model, 'b_paytime', ['inputOptions' => ['class' => "form-control", 'value' => $model->b_paytime ? date('Y-m-d H:i', $model->b_paytime) : '']]);
        echo $form->field($model, 'b_remark');
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
    CLS_FORM.init({url: "<?php echo Url::to(['bill/index']); ?>", url_add: "<?php echo Url::to(['bill/add']); ?>", url_edit: "<?php echo Url::to(['bill/edit']); ?>", url_del: "<?php echo Url::to(['bill/del']); ?>"});

    /* 时间控件 */
    $.datetimepicker.setLocale('zh');
    $('#bill-b_paytime').datetimepicker({
        format: 'Y-m-d H:i',
        step: 5,
        todayButton: true,
    });

    /**
     * 添加
     * @date   2016-11-17
     */
    $('button[adm="submit"]').on('click', function(){
        var _name    = $('#bill-b_name').val();
        var _type    = $('#bill-b_type option:checked').val();
        var _price   = $('#bill-b_price').val();
        var _paytime = $('#bill-b_paytime').val();
        var _remark  = $('#bill-b_remark').val();
        if (!checkForm()) {
            return false;
        }
        var _data = {'Bill[b_name]': _name, 'Bill[b_type]': _type, 'Bill[b_price]': _price, 'Bill[b_paytime]': _paytime, 'Bill[b_remark]': _remark};
        CLS_FORM.submit(CLS_FORM._url_add, _data);
    });

    /* input失去焦点检测 */
    $('#add-form input').on('blur', function(){
        checkForm();
    });
    /* 金额计算 */
    $('#bill-b_price').on('blur', function(){
        var _price = $(this).val();
        if (!CLS_GLOBAL.check_data(_price, 'english')) {
            $(this).val(eval(_price));
        }
    });
});

/**
 * 输入数据检查
 */
function checkForm() {
    var _name    = $('#bill-b_name').val();
    var _price   = $('#bill-b_price').val();
    var _paytime = $('#bill-b_paytime').val();
    if (_name == '') {
        $('.field-bill-b_name').removeClass('has-success').addClass('has-error').find('.help-block').text('消费名目 不能为空！');
        return false;
    }
    $('.field-bill-b_name').removeClass('has-error').addClass('has-success').find('.help-block').text('');
    if (_price == '') {
        $('.field-bill-b_price').removeClass('has-success').addClass('has-error').find('.help-block').text('金额 不能为空！');
        return false;
    }
    $('.field-bill-b_price').removeClass('has-error').addClass('has-success').find('.help-block').text('');
    if (_paytime == '') {
        $('.field-bill-b_paytime').removeClass('has-success').addClass('has-error').find('.help-block').text('消费时间 不能为空！');
        return false;
    }
    $('.field-bill-b_paytime').removeClass('has-error').addClass('has-success').find('.help-block').text('');
    return true;
}
</script>