<?php

namespace app\models\vpn;

use Yii;

/**
 * This is the model class for table "vpn_user_ip_links".
 *
 * @property integer $id
 * @property integer $vpn_user_id
 * @property integer $vpn_ip_id
 *
 * @property VpnUsers $vpnUser
 * @property VpnIpPool $vpnIp
 */
class VpnUserIpLinks extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vpn_user_ip_links';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vpn_user_id', 'vpn_ip_id'], 'required'],
            [['vpn_user_id', 'vpn_ip_id'], 'integer'],
            [['vpn_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => VpnUsers::className(), 'targetAttribute' => ['vpn_user_id' => 'ID']],
            [['vpn_ip_id'], 'exist', 'skipOnError' => true, 'targetClass' => VpnIpPool::className(), 'targetAttribute' => ['vpn_ip_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'vpn_user_id' => 'Vpn User ID',
            'vpn_ip_id' => 'Vpn Ip ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVpnUser()
    {
        return $this->hasOne(VpnUsers::className(), ['ID' => 'vpn_user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVpnIp()
    {
        return $this->hasOne(VpnIpPoolRecord::className(), ['id' => 'vpn_ip_id']);
    }
}
