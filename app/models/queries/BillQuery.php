<?php

namespace zxf\models\queries;

use yii\db\ActiveQuery;
use zxf\models\entities\Bill;

class BillQuery extends ActiveQuery {

    /**
     * 根据ID
     * @author ZhangXueFeng
     * @date   2016年11月17日
     * @param  integer $id
     * @return \zxf\models\queries\BillQuery
     */
    public function byId($id) {
        return $this->andWhere(['b_id' => $id]);
    }

    /**
     * 根据名目
     * @author ZhangXueFeng
     * @date   2016年11月17日
     * @param  string $name
     * @return \zxf\models\queries\BillQuery
     */
    public function byName($name) {
        return $this->andWhere(['like', 'b_name', $name]);
    }

    /**
     * 根据类型
     * @author ZhangXueFeng
     * @date   2016年11月17日
     * @param unknown $type
     * @return \zxf\models\queries\BillQuery
     */
    public function byType($type) {
        return $this->andWhere(['b_type' => $type]);
    }

    /**
     * 是否删除
     * @author ZhangXueFeng
     * @date   2016年11月17日
     * @param  boolean $isDel
     * @return \zxf\models\queries\BillQuery
     */
    public function byIsDel($isDel=FALSE) {
        $isDel = $isDel ? Bill::DEL_YET : Bill::DEL_NOT;
        return $this->andWhere(['b_isdel' => $isDel]);
    }

    /**
     * 根据用户ID
     * @author ZhangXueFeng
     * @date   2016年11月17日
     * @param  integer $uid
     * @return \zxf\models\queries\BillQuery
     */
    public function byUid($uid) {
        return $this->andWhere(['u_id' => $uid]);
    }

    /**
     * 根据用户名
     * @author ZhangXueFeng
     * @date   2016年11月17日
     * @param  string $username
     * @return \zxf\models\queries\BillQuery
     */
    public function byUsername($username) {
        return $this->joinWith(['user' => function($query) use ($username) { $query->andWhere(['u_username' => $username]); }]);
    }

    /**
     * 根据用户姓名
     * @author ZhangXueFeng
     * @date   2016年11月17日
     * @param  string $name
     * @return \zxf\models\queries\BillQuery
     */
    public function byUname($name) {
        return $this->joinWith(['userInfo' => function($query) use ($name) { $query->andWhere(['ui_name' => $name]); }]);
    }
}