<?php

namespace zxf\models\queries;

use yii\db\ActiveQuery;
use zxf\models\entities\UserLog;

class UserLogQuery extends ActiveQuery {

    /**
     * 是否删除
     * @author ZhangXueFeng
     * @date   2016年11月16日
     * @param  boolean $isDel
     * @return \zxf\models\queries\UserLogQuery
     */
    public function byIsDel($isDel=FALSE) {
        $isDel = $isDel ? UserLog::DEL_YET : UserLog::DEL_NOT;
        return $this->andWhere(['ul_isdel' => $isDel]);
    }

    /**
     * 结果
     * @author ZhangXueFeng
     * @date   2016年11月16日
     * @param  integer $status
     * @return \zxf\models\queries\UserLogQuery
     */
    public function byResult($result=NULL) {
        $result === NULL && $result = UserLog::RESULT_OK;
        return $this->andWhere(['ul_result' => $result]);
    }

    /**
     * 根据ID
     * @author ZhangXueFeng
     * @date   2016年11月16日
     * @param  integer $id
     * @return \zxf\models\queries\UserLogQuery
     */
    public function byId($id) {
        return $this->andWhere(['ul_id' => $id]);
    }

    /**
     * 根据用户ID
     * @author ZhangXueFeng
     * @date   2016年11月16日
     * @param  integer $id
     * @return \zxf\models\queries\UserLogQuery
     */
    public function byUid($u_id) {
        return $this->andWhere(['u_id' => $u_id]);
    }

    /**
     * 根据用户名
     * @author ZhangXueFeng
     * @date   2016年11月16日
     * @param  string $username
     * @return \zxf\models\queries\UserLogQuery
     */
    public function byUsername($username) {
        return $this->joinWith(['user' => function($query) use ($username) { $query->andWhere(['u_username' => $username]); }]);
    }

    /**
     * 根据用户姓名
     * @author ZhangXueFeng
     * @date   2016年11月16日
     * @param  string $name
     * @return \zxf\models\queries\UserLogQuery
     */
    public function byName($name) {
        return $this->joinWith(['userInfo' => function($query) use ($name) { $query->andWhere(['ui_name' => $name]); }]);
    }
}