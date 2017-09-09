<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "log".
 *
 * @property integer $ID
 * @property string $MSG
 * @property string $DATENTIME
 * @property string $SOURCE
 */
class LogRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['MSG', 'SOURCE'], 'required'],
            [['DATENTIME'], 'safe'],
            [['MSG'], 'string', 'max' => 1000],
            [['SOURCE'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'MSG' => 'Msg',
            'DATENTIME' => 'Datentime',
            'SOURCE' => 'Source',
        ];
    }
}
