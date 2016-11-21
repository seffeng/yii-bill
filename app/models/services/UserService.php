<?php

namespace zxf\models\services;

use Yii;
use zxf\models\entities\User;
use zxf\models\entities\UserLoginLog;
use zxf\models\entities\UserLog;

class UserService {

    /**
     * 是否登录
     * @author ZhangXueFeng
     * @date   2016年11月16日
     * @return boolean
     */
    public static function isLogin() {
        return Yii::$app->getUser()->getIsGuest() ? FALSE : TRUE;
    }

    /**
     * 登录
     * @author ZhangXueFeng
     * @date   2016年11月16日
     * @return array
     */
    public static function login() {
        $post  = Yii::$app->request->post();
        $form = new User();
        $form->setScenario(ConstService::SCENARIO_LOGIN);
        $return = ['r' => 0, 'd' => NULL, 'm' => '帐号不存在或密码错误！', 'u' => ''];
        if ($form->load($post, '') &&  $form->validate()) {
            $model = self::getByUsername($form->username);
            if ($model) {
                if (password_verify($form->userpass, $model->u_password)) {
                    if ($model->u_status == User::STATUS_OFF) {
                        $return['m'] = '帐号已停用！';
                    } else {
                        Yii::$app->user->login($model);
                        $return['r'] = 1;
                        $return['m'] = '登录成功！';
                        $return['u'] = Yii::$app->homeUrl;
                    }
                }
                $logData = [
                    'u_id'   => $model->u_id,
                    'type'    => UserLoginLog::TYPE_LOGIN,
                    'result'  => $return['r'] == 1 ? UserLoginLog::RESULT_OK : UserLoginLog::RESULT_FAILD,
                    'content' => '登录:'.$return['m'].'[username='.$form->username.']'
                ];
                UserLoginLogService::addLog($logData);
            }
            return $return;
        }
        $return['m'] = FunctionService::getErrorsForString($form);
        return $return;
    }

    /**
     * 注册
     * @author ZhangXueFeng
     * @date   2016年11月16日
     * @return return
     */
    public static function register() {
        $post  = Yii::$app->request->post();
        $model = new User();
        $model->setScenario(ConstService::SCENARIO_INSERT);
        $return = ['r' => 0, 'd' => NULL, 'm' => '注册失败！', 'u' => ''];
        if ($model->load($post) &&  $model->validate() && $model->save()) {
            $return['r'] = 1;
            $return['m'] = '注册成功！';
            $return['u'] = Yii::$app->homeUrl;
            $logData = [
                'u_id'   => $model->u_id,
                'type'    => UserLog::TYPE_ADD_USER,
                'result'  => $return['r'] == 1 ? UserLog::RESULT_OK : UserLog::RESULT_FAILD,
                'content' => '注册:'.$return['m'].'[u_username='.$model->u_username.']'
            ];
            UserLogService::addLog($logData);
            Yii::$app->user->login($model);
            $logData = [
                'u_id'   => $model->u_id,
                'type'    => UserLoginLog::TYPE_LOGIN,
                'result'  => $return['r'] == 1 ? UserLoginLog::RESULT_OK : UserLoginLog::RESULT_FAILD,
                'content' => '登录:'.$return['m'].'[u_username='.$model->u_username.']'
            ];
            UserLoginLogService::addLog($logData);
            return $return;
        }
        $return['m'] = FunctionService::getErrorsForString($model);
        return $return;
    }

    /**
     * 根据用户名查询
     * @author ZhangXueFeng
     * @date   2016年11月16日
     * @param  string $username
     * @return \yii\db\ActiveRecord|NULL
     */
    public static function getByUsername($username) {
        return User::find()->byUsername($username)->byIsDel()->limit(1)->one();
    }

    /**
     * 密码加密
     * @author ZhangXueFeng
     * @date   2016年11月16日
     * @param  string $password 密码
     * @return string
     */
    public static function encryptPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * 是否正常
     * @author ZhangXueFeng
     * @date   2016年11月16日
     * @param  integer $status
     * @return boolean
     */
    public static function statusIsOn($status) {
        return $status == User::STATUS_ON ? TRUE : FALSE;
    }
}