<?php

namespace app\models\vpn;

use Yii;

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
    public function getREQUESTDOC()
    {
        return $this->hasOne(VpnRequestDoc::className(), ['ID' => 'REQUEST_DOC_ID']);
    }
}
