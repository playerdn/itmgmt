<?php

namespace app\rbac;

use app\models\vpn\VpnUsersRecord;

class VpnOwnerRule extends \yii\rbac\Rule {
    public $name='isVpnOwner';
    
    public function execute($user, $item, $params) {
        if(! isset($params['vpnId'])){
            return FALSE;
        }
        $vuid = VpnUsersRecord::GetVpnUserID(\Yii::$app->user->identity->username);

        return (int)$vuid == (int)$params['vpnId'];
    }
}