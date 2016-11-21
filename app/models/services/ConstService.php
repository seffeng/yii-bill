<?php

namespace zxf\models\services;

class ConstService {

    /**
     * Model rules 提示
     */
    const ERROR_RULES_REQUIRE = '{attribute} 不能为空！';
    const ERROR_RULES_EXISTS  = '{attribute} {value} 已存在！';
    const ERROR_RULES_FORMAT  = '{attribute} 格式错误！';

    /**
     * 场景[登录]
     */
    const SCENARIO_LOGIN  = 'login';
    /**
     * 场景[添加]
     */
    const SCENARIO_INSERT = 'insert';
    /**
     * 场景[修改]
     */
    const SCENARIO_UPDATE = 'update';
    /**
     * 场景[查询]
     */
    const SCENARIO_SEARCH = 'search';
}