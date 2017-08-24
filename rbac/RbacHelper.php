<?php

namespace app\rbac;

class RbacHelper {
    static $ROLES_FOR_GROUPS = array(
        'g_inet_admin' => 'inetAdmin',
        'g_mail_admin' => 'mailAdmin',
        'g_vpn_admin' => 'vpnAdmin',
        'g_admins_vks' => 'VcsAdmin',
        'Пользователи домена' => 'user'
    );
    
    // Return roles for specified groups
    public static function GetRolesForGroups ($ldapGroups) {
        $ret = array();
        
        foreach (self::$ROLES_FOR_GROUPS as $group=>$role) {
            if(array_search($group, $ldapGroups) !== false) {
                array_push($ret, $role);
            } 
        }
        
        return $ret;
    }
}