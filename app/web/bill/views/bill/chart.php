<?php
/**
 * 图表
*/

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$this->params['breadcrumb'] = isset($breadcrumb) ? $breadcrumb : [];
$this->title = '账单统计';
?>
<div class="box-body">
    <?php $form = ActiveForm::begin([
        'id'    => 'search-form',
        'options' => ['class' => 'form-inline'],
    ]); ?>
        <div class="box-body">
            <?php
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
    <div id="main_eacharts" style="height:400px;"></div>
</div>
<script type="text/javascript">
$(document).ready(function(){
    /* 初始化 */
    CLS_FORM.init({url: "<?php echo Url::to(['bill/chart']); ?>"});

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

    /**
     * 图表
     */
    var _date    = new Array(<?php echo json_encode($chartData['date']); ?>)[0];
    var _income  = new Array(<?php echo json_encode($chartData['income']); ?>)[0];
    var _expense = new Array(<?php echo json_encode($chartData['expense']); ?>)[0];
    var _income_length  = _income.length;
    var _expense_length = _expense.length;
    var _data_income  = [];
    var _data_expense = [];
    if (_income_length > 0) {
        for (var i=0 in _income) {
            _data_income.push(_income[i] > 0 ? parseFloat(_income[i]) : 0);
        }
    }
    if (_expense_length > 0) {
        for (var i=0 in _expense) {
            _data_expense.push(_expense[i] > 0 ? parseFloat(_expense[i]) : 0);
        }
    }
    var myChart = echarts.init(document.getElementById('main_eacharts'));
    var option = {
            title : {
                text: '收支情况',
            },
            tooltip : {
                trigger: 'axis'
            },
            legend: {
                data:['支出', '收入']
            },
            toolbox: {
                show : true,
                feature : {
                    mark : {show: false},
                    dataView : {show: false, readOnly: false},
                    magicType : {show: true, type: ['line', 'bar']},
                    restore : {show: true},
                    saveAsImage : {show: true}
                }
            },
            xAxis : [
                {
                    type : 'category',
                    boundaryGap : false,
                    data : _date,
                }
            ],
            yAxis : [
                {
                    type : 'value',
                    axisLabel : {
                        formatter: '￥ {value}'
                    }
                }
            ],
            series : [
                {
                    name:'收入',
                    type:'line',
                    data: _data_income,
                    markPoint : {
                        data : [
                            {type : 'max', name: '最大值'},
                            {type : 'min', name: '最小值'}
                        ]
                    },
                    markLine : {
                        data : [
                            {type : 'average', name: '平均值'}
                        ]
                    },
                    itemStyle: {
                        normal: {
                            color: '#5cb85c'
                        }
                    }
                },
                {
                    name:'支出',
                    type:'line',
                    data: _data_expense,
                    markPoint : {
                        data : [
                            {type : 'max', name: '最大值'},
                            {type : 'min', name: '最小值'}
                        ]
                    },
                    markLine : {
                        data : [
                            {type : 'average', name : '平均值'}
                        ]
                    },
                    itemStyle: {
                        normal: {
                            color: '#f0ad4e'
                        }
                    }
                }
            ]
        };
    /* 为echarts对象加载数据 */
    myChart.setOption(option);
});
</script>