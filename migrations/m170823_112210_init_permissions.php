<?php

use yii\db\Migration;

class m170823_112210_init_permissions extends Migration
{
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $auth = \Yii::$app->authManager;
	$auth->removeAll();
	// throw new Exception("123");
			
        $MailAdmin = $auth->createRole('mailAdmin');
        $cMbox = $auth->createPermission('createMailbox');
        $dMbox = $auth->createPermission('deleteMailbox');
        $uMbox = $auth->createPermission('updateMailbox');
        $auth->add($MailAdmin);
        $auth->add($cMbox);
        $auth->add($dMbox);
        $auth->add($uMbox);
        $auth->addChild($MailAdmin, $cMbox);
        $auth->addChild($MailAdmin, $dMbox);
        $auth->addChild($MailAdmin, $uMbox);

        $InetAdmin = $auth->createRole('inetAdmin');
        $grantPerm = $auth->createPermission('grantPermanentAccess');
        $grantTemp = $auth->createPermission('grantTemporaryAccess');
        $removeAccess = $auth->createPermission('removeAccessRule');
        $updateAccess = $auth->createPermission('updateAccessRule');
        $auth->add($InetAdmin);
        $auth->add($grantPerm);
        $auth->add($grantTemp);
        $auth->add($removeAccess);
        $auth->add($updateAccess);
        $auth->addChild($InetAdmin, $grantPerm);
        $auth->addChild($InetAdmin, $grantTemp);
        $auth->addChild($InetAdmin, $removeAccess);
        $auth->addChild($InetAdmin, $updateAccess);

        $VpnAdmin = $auth->createRole('vpnAdmin');
        $viewCred = $auth->createPermission('viewUserCredentials');
        $viewPerms = $auth->createPermission('viewUserPermissions');
        $updatePerms = $auth->createPermission('updatePermissions');
        $deletePerms = $auth->createPermission('deletePermissions');
        $auth->add($VpnAdmin);
        $auth->add($viewCred);
        $auth->add($viewPerms);
        $auth->add($updatePerms);
        $auth->add($deletePerms);
        $auth->addChild($VpnAdmin, $viewCred);
        $auth->addChild($VpnAdmin, $viewPerms);
        $auth->addChild($VpnAdmin, $updatePerms);
        $auth->addChild($VpnAdmin, $deletePerms);

        $user = $auth->createRole('user');
        $ownerRule = new app\rbac\OwnerRule;
        $userViewOwnVpn = $auth->createPermission('viewOwnVPNCredentials');
        $userViewOwnEmail = $auth->createPermission('viewOwnEmailCredentials');
        $userViewOwnVpn->ruleName = $ownerRule->name;
        $userViewOwnEmail->ruleName = $ownerRule->name;
        $auth->add($ownerRule);
        $auth->add($user);
        $auth->add($userViewOwnVpn);
        $auth->add($userViewOwnEmail);
        $auth->addChild($user, $userViewOwnVpn);
        $auth->addChild($user, $userViewOwnEmail);

        $VcsAdmin = $auth->createRole('VcsAdmin');
        $vcsView = $auth->createPermission('viewPortConfig');
        $vcsChange = $auth->createPermission('changePortConfig');
        $auth->add($VcsAdmin);
        $auth->add($vcsView);
        $auth->add($vcsChange);
        $auth->addChild($VcsAdmin, $vcsView);
        $auth->addChild($VcsAdmin, $vcsChange);
    }

    public function down()
    {
			$auth = \Yii::$app->authManager;
			$auth->removeAll();
    }
}
