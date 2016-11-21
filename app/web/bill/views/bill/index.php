<?php
/**
 * 账单列表
*/

use yii\helpers\Url;
use yii\grid\GridView;
use zxf\models\services\BillService;
use zxf\models\entities\Bill;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use zxf\models\services\FunctionService;

$this->params['breadcrumb'] = isset($breadcrumb) ? $breadcrumb : [];
?>
<div class="box-body">
    <?php $form = ActiveForm::begin([
        'id'    => 'search-form',
        'options' => ['class' => 'form-inline'],
    ]); ?>
        <div class="box-body">
            <?php
                echo $form->field($formModel, 'b_type')->dropDownList($typeText, ['class' => 'form-control']);
                echo $form->field($formModel, 'pay_start_date', ['inputOptions' => ['class' => 'form-control', 'placeholder' => '消费时间'], 'labelOptions' => ['class' => 'margin-left-20']]);
                echo $form->field($formModel, 'pay_end_date', ['inputOptions' => ['class' => 'form-control', 'placeholder' => '消费时间']])->label(' - ');
            ?>
            <div class="form-group field-bill-pay_end_date">
                <?php
                    echo Html::button('查询', ['class'=> 'btn btn-info', 'adm' => 'submit']);
                ?>
                <div class="help-block"></div>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
    <hr />
    <?php
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'options'   => ['class' => 'grid-view table-responsive'],
            'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
            'summary'   => '共 {totalCount} 条记录，每页 {count} 条。',
            'layout'    => "{summary}\n{items}\n{pager}",
            'emptyText' => '暂无数据',
            'columns'   => [
                ['header'   => '操作', 'class' => 'yii\grid\ActionColumn', 'options' => ['class' => 'th-sm'], 'template' => '{edit}<span class="margin10"></span>{del}',
                    'buttons'  => [
                        'edit' => function($url, $model) {
                            return '<a class="text-navy" href="javascript:;" title="编辑"><span class="glyphicon glyphicon-pencil" data="'. $model->b_id .'" adm="edit"></span></a>';
                        },
                        'del' => function($url, $model) {
                            return'<a class="text-navy" href="javascript:;" title="删除"><span class="glyphicon glyphicon-trash" data="'. $model->b_id .'" adm="del"></span></a>';
                        },
                    ],
                ],
                ['attribute' => 'b_id'],
                ['attribute' => 'b_name'],
                ['attribute' => 'b_price', 'value' => function($model) {
                    return sprintf('%.2f', $model->b_price / 100);
                }],
                ['attribute' => 'b_type', 'format' => 'raw', 'value' => function($model) {
                    if ($model->b_type == Bill::TYPE_INCOME) {
                        return '<span class="btn btn-success btn-sm" disabled="disabled">'. BillService::getTypeText($model->b_type) .'</span>';
                    } elseif ($model->b_type == Bill::TYPE_EXPENSE) {
                        return '<span class="btn btn-warning  btn-sm" disabled="disabled">'. BillService::getTypeText($model->b_type) .'</span>';
                    }
                    return '-';
                }],
                ['attribute' => 'b_paytime', 'value' => function($model) {
                     return date('Y-m-d H:i', $model->b_paytime);
                }],
                ['attribute' => 'b_addtime', 'value' => function($model) {
                     return date('Y-m-d H:i', $model->b_addtime);
                }],
                ['attribute' => 'b_remark', 'format' => 'raw', 'value' => function($model) {
                    if ($model->b_remark) {
                        return '<a title="'. $model->b_remark .'">'. FunctionService::subString($model->b_remark, 30) .'</a>';
                    } else {
                        return '-';
                    }
                }],
            ],
            'pager' => [
                'firstPageLabel' => '第 1 页',
                'lastPageLabel'  => '第 '. ceil($dataProvider->totalCount / $dataProvider->pagination->pageSize).' 页',
            ],
        ]);
    ?>
</div>
<script>
$(document).ready(function(){
    /* 初始化 */
    CLS_FORM.init({url: "<?php echo Url::to(['bill/index']); ?>", url_add: "<?php echo Url::to(['bill/add']); ?>", url_edit: "<?php echo Url::to(['bill/edit']); ?>", url_del: "<?php echo Url::to(['bill/del']); ?>"});

    /* 时间控件 */
    $.datetimepicker.setLocale('zh');
    $('#bill-pay_start_date').datetimepicker({
        format: 'Y-m-d',
        timepicker:false,
        todayButton: true,
        onShow:function(ct){
            this.setOptions({
                maxDate: $('#bill-pay_end_date').val() ? $('#bill-pay_end_date').val() : false
            })
       },
    });
    /* 时间控件 */
    $('#bill-pay_end_date').datetimepicker({
        format: 'Y-m-d',
        timepicker:false,
        todayButton: true,
        onShow:function(ct){
            this.setOptions({
                minDate: $('#bill-pay_start_date').val() ? $('#bill-pay_start_date').val() : false
            })
       },
    });

    /**
     * ajax 翻页
     * @date   2016-11-4
     */
    $('ul.pagination li a').on('click', function(){
        var _page = parseInt($(this).attr('data-page')) + 1;
        var _url = "<?php $url = Yii::$app->request->getUrl(); $url = parse_url($url); if(isset($url['path'])) { echo $url['path']; } ?>";
        var _query = "<?php if(isset($url['query'])) { echo $url['query']; } ?>";
        var _query_arr = _query.split('&');
        var _data = {page: _page};
        for(var i in _query_arr) {
            var _tmp = _query_arr[i].split('=');
            if (typeof(_tmp[0]) != 'undefined' && typeof(_tmp[1]) != 'undefined' && _tmp[0] != 'page') _data[decodeURIComponent(_tmp[0])] = _tmp[1];
        }
        CLS_MENU.set_data(_data).to_url(_url);
        return false;
    });

    /**
     * 查询
     */
    $('button[adm="submit"]').on('click', function(){
        var _name   = $('#bill-b_name').val();
        var _type   = $('#bill-b_type option:checked').val();
        var _pay_start_date = $('#bill-pay_start_date').val();
        var _pay_end_date   = $('#bill-pay_end_date').val();
        var _data = {'Bill[b_name]': _name, 'Bill[b_type]': _type, 'Bill[pay_start_date]': _pay_start_date, 'Bill[pay_end_date]': _pay_end_date};
        CLS_MENU.reset().set_data(_data).to_url(CLS_FORM._url)
    });
});
</script>