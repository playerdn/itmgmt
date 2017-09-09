<?php

namespace app\models\vpn;

use Yii;
use app\models\WorkstationsRecord;
use app\models\UsersRecord;

/**
 * This is the model class for table "vpn_users".
 *
 * @property integer $ID
 * @property integer $UID
 * @property string $OVPN_CONF_KIT
 * @property string $CERT_PASS
 * @property integer $REQUEST_DOC_ID
 * @property string $START_DATE
 * @property string $EXPIRATION
 * @property string $LAST_ACCESS
 *
 * @property VpnRdpAccess[] $vpnRdpAccesses
 * @property VpnUserIpLinks[] $vpnUserIpLinks
 * @property Users $u
 * @property VpnRequestDoc $rEQUESTDOC
 */
class VpnUsersRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vpn_users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['UID'], 'required'],
            [['UID', 'REQUEST_DOC_ID'], 'integer'],
            [['OVPN_CONF_KIT'], 'string'],
            [['START_DATE', 'EXPIRATION', 'LAST_ACCESS'], 'safe'],
            [['CERT_PASS'], 'string', 'max' => 40],
            [['UID'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\UsersRecord::className(), 'targetAttribute' => ['UID' => 'id']],
            [['REQUEST_DOC_ID'], 'exist', 'skipOnError' => true, 'targetClass' => VpnRequestDocRecord::className(), 'targetAttribute' => ['REQUEST_DOC_ID' => 'ID']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'UID' => 'Uid',
            'OVPN_CONF_KIT' => 'Ovpn  Conf  Kit',
            'CERT_PASS' => 'Cert  Pass',
            'REQUEST_DOC_ID' => 'Request  Doc  ID',
            'START_DATE' => 'Start  Date',
            'EXPIRATION' => 'Expiration',
            'LAST_ACCESS' => 'Last  Access',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVpnRdpAccesses()
    {
        return $this->hasMany(VpnRdpAccess::className(), ['VPN_UID' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVpnUserIpLinks()
    {
        return $this->hasMany(VpnUserIpLinks::className(), ['vpn_user_id' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\app\models\UsersRecord::className(), ['id' => 'UID']);
    }

    public function getUserName() {
        return $this->user->ADLogin;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequestDoc()
    {
        return $this->hasOne(VpnRequestDocRecord::className(), ['ID' => 'REQUEST_DOC_ID']);
    }
    
    public function getVpnIPs(){
        return $this->
                hasMany(VpnIpPoolRecord::className(), ['ID' => 'vpn_ip_id'])->via('vpnUserIpLinks');
    }
    public function getAllowedWorkstations() {
        return $this->hasMany(\app\models\WorkstationsRecord::className(), ['ID' => 'WSID'])
                ->via('vpnRdpAccesses')
                ->orderBy(['name' => SORT_ASC]);
    }
    
    /**
     * Get VPN user ID by username
     * 
     * Function returns ID from `vpn_users` table
     * 
     * @param string $username Username (must exists in `users` table)
     * @return integer
     */
    public static function GetVpnUserID($username) {
        $user = UsersRecord::findOne(['ADLogin' => $username]);
        if(! $user) {
            return null;
        }
        
        $vuser = VpnUsersRecord::findOne(['UID' => $user->id]);
        if($vuser) {
            return $vuser->ID;
        } else {
            return null;
        }
    }
    /**
     * 
     * @return string Formated string for displaying in interface
     */
    public function getAllowedWorkstationsAsString() {
        $ret = '';
        foreach ($this->allowedWorkstations as $ws) {
            if ($ret == '') {
                $ret .= $ws->name . ' (' . $ws->ip . ')';
            } else {
                $ret .= "\n\n" . $ws->name . ' (' . $ws->ip . ')';
            }
        }
        return $ret;
    }
}
