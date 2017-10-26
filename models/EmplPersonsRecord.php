<?php

namespace app\models;

use Yii;
use app\models\mail\MailRecord;

/**
 * This is the model class for table "empl.persons".
 *
 * @property string $ID
 * @property string $nrec
 * @property string $Фамилия
 * @property string $Имя
 * @property string $Отчество
 * @property string $Birthday
 * @property string $Sex
 * @property string $DisDate
 * @property integer $base_id
 * @property string $curappdate
 * @property string $firstappdate
 * @property string $fioukr
 * @property string $Birthdayfakt
 * @property integer $dp
 */
class EmplPersonsRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'empl.persons';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('dba2t');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID', 'nrec', 'Фамилия', 'Имя', 'Отчество', 'DisDate', 'dp'], 'required'],
            [['ID', 'Фамилия', 'Имя', 'Отчество', 'Sex', 'fioukr'], 'string'],
            [['nrec'], 'number'],
            [['Birthday', 'DisDate', 'curappdate', 'firstappdate', 'Birthdayfakt'], 'safe'],
            [['base_id', 'dp'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'nrec' => 'Nrec',
            'Фамилия' => 'Фамилия',
            'Имя' => 'Имя',
            'Отчество' => 'Отчество',
            'Birthday' => 'Birthday',
            'Sex' => 'Sex',
            'DisDate' => 'Dis Date',
            'base_id' => 'Base ID',
            'curappdate' => 'Curappdate',
            'firstappdate' => 'Firstappdate',
            'fioukr' => 'Fioukr',
            'Birthdayfakt' => 'Birthdayfakt',
            'dp' => 'Dp',
        ];
    }
    
    public static function EmployersWitoutEmail() {
        $todaySql = date('Ymd');
        
        $emplz = EmplPersonsRecord::find()->where(['>', 'DisDate', $todaySql])->all();
        $emplzEmail = MailRecord::find()->where(['is not', 'guid', null])->all();
        
        $emplzGuidz = array();
        $emailzGuidz = array();
        
        foreach($emplz as $empl) {$emplzGuidz[] = strtolower($empl->ID);}
        foreach($emplzEmail as $empl) {$emailzGuidz[] = strtolower($empl->guid);}
        
        $haveEmailz = array_unique(array_intersect($emailzGuidz, $emplzGuidz));
        
        foreach($emplz as $empl) {
            if(array_search(strtolower($empl->ID), $haveEmailz) === FALSE) {
                $ret[]=$empl;
            }
        }
        
        usort($ret, ["app\models\EmplPersonsRecord", "SortByName"]);
        return $ret;
    }
    protected static function SortByName($a, $b) {
        $aFio = strtolower($a->Фамилия . $a->Имя . $a->Отчество);
        $bFio = strtolower($b->Фамилия . $b->Имя . $b->Отчество);
        
        return strcmp($aFio, $bFio);
    }
    public function getfullName(){
        return $this->Фамилия . " " . $this->Имя . " " . $this->Отчество;
    }
}
