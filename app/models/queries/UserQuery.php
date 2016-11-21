<?php

namespace zxf\models\queries;

use yii\db\ActiveQuery;
use zxf\models\entities\User;

class UserQuery extends ActiveQuery {

    /**
     * 根据ID查询
     * @author ZhangXueFeng
     * @date   2016年11月16日
     * @param  integer $id
     * @return \zxf\models\queries\UserQuery
     */
    public function byId($id) {
        return $this->andWhere(['u_id' => $id]);
    }

    /**
     * 用户名条件
     * @author ZhangXueFeng
     * @date   2016年11月16日
     * @param  string $username 用户名
     * @return \zxf\models\queries\UserQuery
     */
    public function byUsername($username) {
        return $this->andWhere(['u_username' => $username]);
    }

    /**
     * 是否删除
     * @author ZhangXueFeng
     * @date   2016年11月16日
     * @param  boolean $isDel 是否删除 default[FALSE]
     * @return \zxf\models\queries\UserQuery
     */
    public function byIsDel($isDel=FALSE) {
        $isDel = $isDel ? User::DEL_YET : User::DEL_NOT;
        return $this->andWhere(['u_isdel' => $isDel]);
    }

    /**
     * 状态
     * @author ZhangXueFeng
     * @date   2016年11月16日
     * @param  integer $status 状态 default[NULL]
     * @return \zxf\models\queries\UserQuery
     */
    public function byStatus($status=NULL) {
        $status === NULL && $status = User::STATUS_ON;
        return $this->andWhere(['u_status' => $status]);
    }

    /**
     * 根据姓名
     * @author ZhangXueFeng
     * @date   2016年11月16日
     * @param  string $name
     * @return \zxf\models\queries\UserQuery
     */
    public function byName($name) {
        return $this->joinWith(['userInfo' => function($query) use ($name) { $query->andWhere(['ui_name' => $name]); }]);
    }
}