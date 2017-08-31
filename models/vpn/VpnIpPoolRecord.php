<?php

namespace app\models\vpn;

use Yii;

/**
 * This is the model class for table "vpn_ip_pool".
 *
 * @property string $ip
 * @property integer $id
 *
 * @property VpnUserIpLinks[] $vpnUserIpLinks
 */
class VpnIpPoolRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vpn_ip_pool';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ip'], 'required'],
            [['ip'], 'string', 'max' => 15],
            [['ip'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ip' => 'Ip',
            'id' => 'ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVpnUserIpLinks()
    {
        return $this->hasMany(VpnUserIpLinks::className(), ['vpn_ip_id' => 'id']);
    }
}
