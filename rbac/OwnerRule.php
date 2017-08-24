<?php

namespace app\rbac;

class OwnerRule extends \yii\rbac\Rule {
    public $name='isOwner';
    
    public function execute($user, $item, $params) {
        return isset($params['username']) ? \Yii::$app->user->identity->username == $params['username']:false;
    }
}