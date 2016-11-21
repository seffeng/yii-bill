<?php
/**
 * 登录
*/

use yii\widgets\ActiveForm;
use zxf\web\bill\components\LoginAsset;
use yii\helpers\Url;

LoginAsset::register($this);
LoginAsset::addScriptForIE($this);

$this->context->layout = FALSE;
$this->title = '登录';
$web_url = Yii::getAlias('@web');
$this->beginPage();
?>
<!DOCTYPE html>
<html lang="<?php echo Yii::$app->language; ?>">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <title><?php echo ($this->title ? ($this->title .' - ') : '') . Yii::$app->name; ?></title>
        <?php $this->head(); ?>
    </head>

    <body class="hold-transition login-page">
        <?php $this->beginBody(); ?>
        <div class="alert-box"></div>
        <div class="login-box" id="main_content">
            <div class="login-logo">
                <a href="javascript:;"><b>用户</b>中心</a>
            </div><!-- /.login-logo -->
            <div class="login-box-body">
                <p class="login-box-msg">登录</p>
                <?php
                    $form = ActiveForm::begin([
                        'id' => 'login-form',
                    ]);
                ?>
                    <div class="form-group has-feedback">
                        <input type="text" class="form-control" id="username" name="username" value="" placeholder="用户名">
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" class="form-control" id="userpass" name="userpass" value="" placeholder="密码">
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <div class="row">
                        <div class="col-xs-4">
                            <a class="btn btn-block btn-flat" href="<?php echo Url::to(['site/register']); ?>">注册</a>
                        </div><!-- /.col -->
                        <div class="col-xs-4"></div>
                        <div class="col-xs-4">
                            <button type="button" class="btn btn-primary btn-block btn-flat" id="login_btn">登录</button>
                        </div><!-- /.col -->
                    </div>
                <?php ActiveForm::end(); ?>
            </div><!-- /.login-box-body -->
        </div><!-- /.login-box -->
        <?php
        $this->registerJs(<<<js
            CLS_LOGIN.init({user_id : 'username', pass_id : 'userpass', btn_id : 'login_btn', 'login_url' : ''});
            $('input').iCheck({
                    checkboxClass: 'icheckbox_square-blue',
                    radioClass: 'iradio_square-blue',
                    increaseArea: '20%' // optional
                });
js
            );
        ?>
        <?php $this->endBody(); ?>
    </body>
</html>
<?php $this->endPage(); ?>