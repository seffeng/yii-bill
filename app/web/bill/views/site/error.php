<?php
use yii\helpers\Url;

$this->title = '错误提示';
?>
<div class="box-body">
    <div class="error-page">
        <h2 class="headline text-yellow"> <?php echo $status; ?></h2>
        <div class="error-content">
            <h3><i class="fa fa-warning text-yellow"></i> <?php echo $message; ?></h3>
            <p><a href="<?php echo Url::to(['/']); ?>">返回首页</a></p>
        </div>
    </div>
</div>