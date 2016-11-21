<?php

namespace zxf\models\queries;

use yii\db\ActiveQuery;
use zxf\models\entities\UserLoginLog;

class UserLoginLogQuery extends ActiveQuery {

    /**
     * 是否删除
     * @author ZhangXueFeng
     * @date   2016年11月16日
     * @param  boolean $isDel
     * @return \zxf\models\queries\UserLoginLogQuery
     */
    public function byIsDel($isDel=FALSE) {
        $isDel = $isDel ? UserLoginLog::DEL_YET : UserLoginLog::DEL_NOT;
        return $this->andWhere(['ull_isdel' => $isDel]);
    }

    /**
     * 结果
     * @author ZhangXueFeng
     * @date   2016年11月16日
     * @param  integer $status
     * @return \zxf\models\queries\UserLoginLogQuery
     */
    public function byResult($result=NULL) {
        $result === NULL && $result = UserLoginLog::RESULT_OK;
        return $this->andWhere(['ull_result' => $result]);
    }

    /**
     * 根据ID
     * @author ZhangXueFeng
     * @date   2016年11月16日
     * @param  integer $id
     * @return \zxf\models\queries\UserLoginLogQuery
     */
    public function byId($id) {
        return $this->andWhere(['ull_id' => $id]);
    }

    /**
     * 根据用户ID
     * @author ZhangXueFeng
     * @date   2016年11月16日
     * @param  integer $id
     * @return \zxf\models\queries\UserLoginLogQuery
     */
    public function byUid($u_id) {
        return $this->andWhere(['u_id' => $u_id]);
    }

    /**
     * 根据用户名
     * @author ZhangXueFeng
     * @date   2016年11月16日
     * @param  string $username
     * @return \zxf\models\queries\UserLoginLogQuery
     */
    public function byUsername($username) {
        return $this->joinWith(['user' => function($query) use ($username) { $query->andWhere(['u_username' => $username]); }]);
    }

    /**
     * 根据用户姓名
     * @author ZhangXueFeng
     * @date   2016年11月16日
     * @param  string $name
     * @return \zxf\models\queries\UserLoginLogQuery
     */
    public function byName($name) {
        return $this->joinWith(['userInfo' => function($query) use ($name) { $query->andWhere(['ui_name' => $name]); }]);
    }
}