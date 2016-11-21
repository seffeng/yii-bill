<?php
/**
 * APP控制器
 */

namespace zxf\web\bill\components;

use Yii;
use zxf\components\WebController;
use zxf\models\services\UserService;
use yii\helpers\Json;

class Controller extends WebController {

    /**
     * @var boolean whether to enable CSRF validation for the actions in this controller.
     * CSRF validation is enabled only when both this property and [[\yii\web\Request::enableCsrfValidation]] are true.
     */
    public $enableCsrfValidation = FALSE;

    /**
     * 
     * {@inheritDoc}
     * @see \zxf\components\WebController::beforeAction()
     */
    public function beforeAction($action) {
        if (parent::beforeAction($action)) {
            $actionId = $action->id;
            $controllerId  = $action->controller->id;
            $controllerAction = $controllerId .'/'. $actionId;
            $ignoreLogin   = ['site/login', 'site/index', 'site/register'];    /* 忽略登录 */
            if (!in_array($controllerAction, $ignoreLogin)) {
                if (!UserService::isLogin()) {
                    if (Yii::$app->request->isAjax) {
                        die(Json::encode(['r' => 0, 'm' => '身份验证失败，请重新登录！']));
                    }
                    $this->redirect(Yii::$app->getUser()->loginUrl);
                }
            }
            return TRUE;
        }
        return FALSE;
    }
}