<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "workstations".
 *
 * @property integer $id
 * @property string $name
 * @property string $mac
 * @property string $ip
 * @property string $DATENTIME
 *
 * @property PcLogins[] $pcLogins
 * @property VpnRdpAccess[] $vpnRdpAccesses
 */
class WorkstationsRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'workstations';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ip'], 'required'],
            [['DATENTIME'], 'safe'],
            [['name'], 'string', 'max' => 50],
            [['mac'], 'string', 'max' => 17],
            [['ip'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'mac' => 'Mac',
            'ip' => 'Ip',
            'DATENTIME' => 'Datentime',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPcLogins()
    {
        return $this->hasMany(PcLogins::className(), ['ws_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVpnRdpAccesses()
    {
        return $this->hasMany(VpnRdpAccess::className(), ['WSID' => 'id']);
    }
}
