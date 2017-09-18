<?php

namespace app\models\vpn;

use Yii;
use app\models\WorkstationsRecord;

/**
 * This is the model class for table "vpn_rdp_access".
 *
 * @property integer $ID
 * @property integer $WSID
 * @property integer $VPN_UID
 *
 * @property Workstations $wS
 * @property VpnUsers $vPNU
 */
class VpnRdpAccessRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vpn_rdp_access';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['WSID', 'VPN_UID'], 'required'],
            [['WSID', 'VPN_UID'], 'integer'],
            [['WSID'], 'exist', 'skipOnError' => true, 'targetClass' => WorkstationsRecord::className(), 'targetAttribute' => ['WSID' => 'id']],
            [['VPN_UID'], 'exist', 'skipOnError' => true, 'targetClass' => VpnUsersRecord::className(), 'targetAttribute' => ['VPN_UID' => 'ID']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'WSID' => 'Wsid',
            'VPN_UID' => 'Vpn  Uid',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWS()
    {
        return $this->hasOne(Workstations::className(), ['id' => 'WSID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVPNU()
    {
        return $this->hasOne(VpnUsers::className(), ['ID' => 'VPN_UID']);
    }
}
