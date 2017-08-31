<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property integer $id
 * @property string $ADLogin
 * @property string $ASUPDID
 * @property string $DATENTIME
 * @property string $CREATEDFROM
 *
 * @property PcLogins[] $pcLogins
 * @property VpnUsers[] $vpnUsers
 */
class UsersRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ADLogin'], 'required'],
            [['DATENTIME'], 'safe'],
            [['ADLogin', 'ASUPDID', 'CREATEDFROM'], 'string', 'max' => 50],
            [['ADLogin'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ADLogin' => 'Adlogin',
            'ASUPDID' => 'Asupdid',
            'DATENTIME' => 'Datentime',
            'CREATEDFROM' => 'Createdfrom',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPcLogins()
    {
        return $this->hasMany(PcLogins::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVpnUsers()
    {
        return $this->hasMany(VpnUsers::className(), ['UID' => 'id']);
    }
}
