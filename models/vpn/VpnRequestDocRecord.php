<?php

namespace app\models\vpn;

use Yii;

/**
 * This is the model class for table "vpn_request_doc".
 *
 * @property integer $ID
 * @property string $PDF_OBJECT
 * @property string $DOC_DATE
 * @property string $DESCRIPTION
 *
 * @property VpnUsers[] $vpnUsers
 */
class VpnRequestDocRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vpn_request_doc';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['PDF_OBJECT'], 'required'],
            [['PDF_OBJECT'], 'string'],
            [['DOC_DATE'], 'safe'],
            [['DESCRIPTION'], 'string', 'max' => 512],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'PDF_OBJECT' => 'Pdf  Object',
            'DOC_DATE' => 'Doc  Date',
            'DESCRIPTION' => 'Description',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVpnUsers()
    {
        return $this->hasMany(VpnUsers::className(), ['REQUEST_DOC_ID' => 'ID']);
    }
}
