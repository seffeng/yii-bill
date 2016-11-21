<?php

namespace zxf\models\entities;

use Yii;
use zxf\components\ActiveRecord;
use zxf\models\queries\UserQuery;
use yii\helpers\ArrayHelper;
use zxf\models\services\ConstService;
use zxf\models\services\FunctionService;
use zxf\models\services\UserService;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface {

    /**
     * 启用
     * u_status
     */
    const STATUS_ON  = 1;
    /**
     * 停用
     * u_status
     */
    const STATUS_OFF = 2;
    /**
     * 状态说明
     */
    const STATUS_TEXT = [
        self::STATUS_ON  => '启用',
        self::STATUS_OFF => '停用',
    ];

    /**
     * 已删除
     * u_isdel
     */
    const DEL_YET = 1;
    /**
     * 未删除
     * u_isdel
     */
    const DEL_NOT = 2;

    /**
     * 表名
     * @author ZhangXueFeng
     * @date   2016年11月16日
     * @return string
     */
    public static function tableName() {
        return '{{%user}}';
    }

    /**
     * 重写 find()
     * @author ZhangXueFeng
     * @date   2016年11月16日
     * @return unknown
     */
    public static function find() {
        return Yii::createObject(UserQuery::className(), [get_called_class()]);
    }

    /**
     *
     * {@inheritDoc}
     * @see \yii\db\ActiveRecord::attributes()
     */
    public function attributes() {
        return ArrayHelper::merge(parent::attributes(), [
            'username', 'userpass'
        ]);
    }

    /**
     *
     * {@inheritDoc}
     * @see \yii\base\Model::attributeLabels()
     */
    public function attributeLabels() {
        return [
            'username' => '用户名',
            'userpass' => '密码',
            'u_id'       => 'ID',
            'u_username' => '用户名',
            'u_password' => '密码',
            'ug_id'      => '管理员组',
            'u_status'   => '状态',
            'u_addtime'  => '添加时间',
            'u_lasttime' => '修改时间',
        ];
    }

    /**
     *
     * {@inheritDoc}
     * @see \yii\base\Model::scenarios()
     */
    public function scenarios() {
        return ArrayHelper::merge(parent::scenarios(), [
            ConstService::SCENARIO_LOGIN   => ['username', 'userpass'],
        ]);
    }

    /**
     *
     * {@inheritDoc}
     * @see \yii\base\Model::rules()
     */
    public function rules() {
        return [
            [['username', 'userpass'], 'required', 'on' => ConstService::SCENARIO_LOGIN, 'message' => ConstService::ERROR_RULES_REQUIRE],
            [['u_username', 'u_password'], 'required', 'on' => [ConstService::SCENARIO_INSERT], 'message' => ConstService::ERROR_RULES_REQUIRE],
            [['u_username'], 'required', 'on' => [ConstService::SCENARIO_UPDATE], 'message' => ConstService::ERROR_RULES_REQUIRE],
            [['u_username', 'u_password'], 'string', 'on' => [ConstService::SCENARIO_INSERT, ConstService::SCENARIO_UPDATE], 'message' => ConstService::ERROR_RULES_FORMAT],
            [['u_username'], 'unique', 'filter' => ['u_isdel' => self::DEL_NOT], 'on' => [ConstService::SCENARIO_INSERT, ConstService::SCENARIO_UPDATE], 'message' => ConstService::ERROR_RULES_EXISTS],
            [['u_status', 'ug_id'], 'integer', 'on' => [ConstService::SCENARIO_INSERT, ConstService::SCENARIO_UPDATE], 'message' => ConstService::ERROR_RULES_FORMAT],
            ['u_password', 'checkAttribute', 'on' => [ConstService::SCENARIO_INSERT, ConstService::SCENARIO_UPDATE]],
        ];
    }

    /**
     *
     * {@inheritDoc}
     * @see \yii\db\BaseActiveRecord::beforeSave()
     */
    public function beforeSave($insert) {
        $ipLong = ip2long(FunctionService::getUserIP());
        if ($insert) {
            $this->u_addtime  = THIS_TIME;
            $this->u_addip    = $ipLong;
            $this->u_password = UserService::encryptPassword($this->u_password);
        } else {
            if ($this->u_password && $this->u_password !== $this->getOldAttribute('u_password')) {
                $this->u_password = UserService::encryptPassword($this->u_password);
            } else {
                unset($this->u_password);
            }
        }
        $this->u_lasttime = THIS_TIME;
        $this->u_lastip   = $ipLong;
        return parent::beforeSave($insert);
    }

    /**
     * 字段检测
     * @author ZhangXueFeng
     * @date   2016年11月16日
     * @param  string $attribute
     */
    public function checkAttribute($attribute) {
        switch ($attribute) {
            case 'u_password' : {
                if (!FunctionService::checkData($this->u_password, 'password')) {
                    $this->addError($attribute, '密码 格式错误！');
                }
                break;
            }
        }
    }

    /**
     * 关联用户组
     * @author ZhangXueFeng
     * @date   2016年11月16日
     * @return \yii\db\ActiveQuery
     */
    public function getUserGroup() {
        return $this->hasOne(UserGroup::className(), ['ug_id' => 'ug_id']);
    }

    /**
     * 关联用户信息
     * @author ZhangXueFeng
     * @date   2016年11月16日
     * @return \yii\db\ActiveQuery
     */
    public function getUserInfo() {
        return $this->hasOne(UserInfo::className(), ['u_id' => 'u_id']);
    }

    /**
     *
     * @author ZhangXueFeng
     * @date   2016年11月16日
     * @param  integer $id
     * @return \zxf\models\entities\Admin|NULL
     */
    public static function findIdentity($id) {
        return self::find()->byId($id)->byIsDel()->byStatus()->limit(1)->one();
    }

    /**
     *
     * {@inheritDoc}
     * @see \yii\web\IdentityInterface::findIdentityByAccessToken()
     */
    public static function findIdentityByAccessToken($token, $type=NULL) {
        return NULL;
    }

    /**
     * 当前登录用户ID
     * {@inheritDoc}
     * @see \yii\web\IdentityInterface::getId()
     */
    public function getId() {
        return $this->u_id;
    }

    /**
     *
     * {@inheritDoc}
     * @see \yii\web\IdentityInterface::getAuthKey()
     */
    public function getAuthKey() {
        return '';
    }

    /**
     *
     * {@inheritDoc}
     * @see \yii\web\IdentityInterface::validateAuthKey()
     */
    public function validateAuthKey($authKey) {
        return TRUE;
    }
}