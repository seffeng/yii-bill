<?php


use yii\widgets\ActiveForm;
use zxf\web\bill\components\LoginAsset;
use yii\helpers\Url;

LoginAsset::register($this);
LoginAsset::addScriptForIE($this);

$this->context->layout = FALSE;
$web_url = Yii::getAlias('@web');
$this->title = '注册';
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

    <body class="hold-transition login-page"><?php $this->beginBody(); ?>
        <div class="alert-box"></div>
        <div class="login-box" id="main_content">
            <div class="login-logo">
                <a href="javascript:;"><b>用户</b>中心</a>
            </div><!-- /.login-logo -->
            <div class="login-box-body">
                <p class="login-box-msg">注册帐号</p>
                <?php
                    $form = ActiveForm::begin([
                        'id' => 'register-form',
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
                    <div class="form-group has-feedback">
                        <input type="password" class="form-control" id="userpass_c" name="userpass_c" value="" placeholder="确认密码">
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <div class="row">
                        <div class="col-xs-4">
                            <a class="btn btn-block btn-flat" href="<?php echo Url::to(['site/login']); ?>">登录</a>
                        </div><!-- /.col -->
                        <div class="col-xs-4"></div>
                        <div class="col-xs-4">
                            <button type="button" class="btn btn-primary btn-block btn-flat" id="register_btn">注册</button>
                        </div><!-- /.col -->
                    </div>
                <?php ActiveForm::end(); ?>
            </div><!-- /.login-box-body -->
        </div><!-- /.login-box -->
        <?php
        $url = Url::to(['/site/register']);
        $this->registerJs(<<<js
            /* 注册 */
            $('#register_btn').on('click', function(){
                var _username = $('#username').val();
                var _userpass = $('#userpass').val();
                var _userpass_c = $('#userpass_c').val();
                var _url = "{$url}";
                if (_username == '') {
                    CLS_ALERT.show('请输入用户名');
                    return false;
                }
                if (_userpass == '') {
                    CLS_ALERT.show('请输入密码');
                    return false;
                }
                if (_userpass != _userpass_c) {
                    CLS_ALERT.show('两次密码不一致');
                    return false;
                }
                $.ajax({
                    url: _url,
                    data: {'User[u_username]': _username, 'User[u_password]': _userpass},
                    type: 'POST',
                    dataType: 'json',
                    success: function(_res) {
                        if (_res.r == 1) {
                            CLS_ALERT.show({text: _res.m, type: 'success'});
                            window.location.href = _res.u;
                            return false;
                        } else {
                            CLS_ALERT.show(_res.m);
                        }
                        CLS_ALERT.unload();
                    },
                    beforeSend: function() {
                        CLS_ALERT.loading();
                    },
                    error : function() {
                        CLS_ALERT.show('异常错误');
                        CLS_ALERT.unload();
                    }
                });
            });
            /* 回车事件 */
            $('input').on('keypress', function(e) {
                if (e.keyCode == 13) {
                    $('#register_btn').click();
                }
            });
js
            );
        ?>
        <?php $this->endBody(); ?>
    </body>
</html>
<?php $this->endPage(); ?>