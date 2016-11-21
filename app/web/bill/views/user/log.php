<?php
/**
 * 日志列表
*/

use yii\helpers\Url;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use zxf\models\services\UserLogService;
use zxf\models\entities\UserLog;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->params['breadcrumb'] = isset($breadcrumb) ? $breadcrumb : [];
?>
<div class="box-body">
    <?php $form = ActiveForm::begin([
        'id'    => 'search-form',
        'options' => ['class' => 'form-inline'],
    ]); ?>
        <div class="box-body">
            <?php
                echo $form->field($formModel, 'result')->dropDownList($resultText, ['class' => 'form-control']);
                echo $form->field($formModel, 'add_start_date', ['inputOptions' => ['class' => 'form-control', 'placeholder' => '添加时间'], 'labelOptions' => ['class' => 'margin-left-20']]);
                echo $form->field($formModel, 'add_end_date', ['inputOptions' => ['class' => 'form-control', 'placeholder' => '添加时间']])->label(' - ');
            ?>
            <div class="form-group field-userlog-add_end_date">
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
                ['attribute' => 'ul_id'],
                ['attribute' => 'u_id', 'value' => function($model) {
                    return ArrayHelper::getValue($model, 'user.u_username', '-').'('.'ID: '. $model->u_id .'; '. ArrayHelper::getValue($model, 'user.userInfo.ui_name', '-') .')';
                }],
                ['attribute' => 'ul_result', 'format' => 'raw', 'value' => function($model) {
                    if ($model->ul_result == UserLog::RESULT_OK) {
                        return '<span class="btn btn-success btn-sm" disabled="disabled">'. UserLogService::getResultText($model->ul_result) .'</span>';
                    } elseif ($model->ul_result == UserLog::RESULT_FAILD) {
                        return '<span class="btn btn-warning  btn-sm" disabled="disabled">'. UserLogService::getResultText($model->ul_result) .'</span>';
                    }
                    return '-';
                }],
                ['attribute' => 'ul_content'],
                ['attribute' => 'ul_addtime', 'value' => function($model) {
                     return date('Y-m-d H:i', $model->ul_addtime);
                }],
                ['attribute' => 'ul_addip', 'value' => function($model) {
                     return long2ip($model->ul_addip);
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
    CLS_FORM.init({url: "<?php echo Url::to(['user/log']); ?>"});

    /* 时间控件 */
    $.datetimepicker.setLocale('zh');
    $('#userlog-add_start_date').datetimepicker({
        format: 'Y-m-d',
        timepicker:false,
        todayButton: true,
        onShow:function(ct){
            this.setOptions({
                maxDate: $('#userlog-add_end_date').val() ? $('#userlog-add_end_date').val() : false
            })
       },
    });
    /* 时间控件 */
    $('#userlog-add_end_date').datetimepicker({
        format: 'Y-m-d',
        timepicker:false,
        todayButton: true,
        onShow:function(ct){
            this.setOptions({
                minDate: $('#userlog-add_start_date').val() ? $('#userlog-add_start_date').val() : false
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
        var _u_id     = $('#userlog-u_id').val();
        var _username = $('#userlog-username').val();
        var _name     = $('#userlog-name').val();
        var _result   = $('#userlog-result option:checked').val();
        var _add_start_date = $('#userlog-add_start_date').val();
        var _add_end_date   = $('#userlog-add_end_date').val();
        var _data = {'UserLog[u_id]': _u_id, 'UserLog[username]': _username, 'UserLog[name]': _name, 'UserLog[result]': _result, 'UserLog[add_start_date]': _add_start_date, 'UserLog[add_end_date]': _add_end_date};
        CLS_MENU.reset().set_data(_data).to_url(CLS_FORM._url)
    });
});
</script>