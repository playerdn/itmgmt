<?php

namespace app\rbac;

use app\models\vpn\VpnUsersRecord;

class EmailOwnerRule extends \yii\rbac\Rule {
    public $name='isEmailOwner';
    
    public function execute($user, $item, $params) {
        return isset($params['username']) ? \Yii::$app->user->identity->username == $params['username']:false;
    }
}