<?php

namespace app\models\mail;

use Yii;
use app\helpers\EmailHelpers;

/**
 * This is the model class for table "mail".
 *
 * @property integer $id
 * @property string $name_f
 * @property string $name_i
 * @property string $name_o
 * @property string $guid
 * @property string $login
 * @property string $E_mail
 * @property string $passwd
 * @property string $ip
 * @property string $date_cr
 * @property string $spam_f
 * @property string $greylist
 * @property integer $IsLocal
 * @property string $tel
 * @property integer $visible_mail
 * @property string $komn
 * @property string $otdel
 * @property string $aliases
 * @property string $IsEnabled
 * @property string $IsDismiss
 */
class MailRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mail';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('dbMail');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date_cr'], 'safe'],
            [['IsLocal', 'visible_mail'], 'integer'],
            [['IsEnabled', 'IsDismiss'], 'string'],
            [['name_f'], 'string', 'max' => 64],
            [['name_i', 'name_o', 'login', 'passwd', 'tel', 'komn', 'otdel'], 'string', 'max' => 20],
            [['guid'], 'string', 'max' => 36],
            [['E_mail'], 'string', 'max' => 40],
            [['ip'], 'string', 'max' => 200],
            [['spam_f', 'greylist'], 'string', 'max' => 1],
            ['aliases', 'checkAliases'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name_f' => 'Name F',
            'name_i' => 'Name I',
            'name_o' => 'Name O',
            'guid' => 'Guid',
            'login' => 'Login',
            'E_mail' => 'E Mail',
            'passwd' => 'Passwd',
            'ip' => 'Ip',
            'date_cr' => 'Date Cr',
            'spam_f' => 'Spam F',
            'greylist' => 'Greylist',
            'IsLocal' => 'Is Local',
            'tel' => 'Tel',
            'visible_mail' => 'Visible Mail',
            'komn' => 'Komn',
            'otdel' => 'Otdel',
            'aliases' => 'Aliases',
            'IsEnabled' => 'Is Enabled',
            'IsDismiss' => 'Is Dismiss',
        ];
    }
    /**
     * Validation for aliases fied
     * 
     * @param type $attribute
     * @param type $params
     * @return void
     */
    public function checkAliases($attribute, $params) {
        $emails = split(",", $this->$attribute);
        $validator = new \yii\validators\EmailValidator();

        foreach($emails as $email) {
            $email = trim($email);
            if(!$validator->validate($email)) {
                $this->addError($attribute, 'Not email: "'.$email.'"');
                return;
            }
            
            if(EmailHelpers::IsOurEmail($email)){
                // Check existing
                if(MailRecord::find()->
                    filterWhere(['like', 'E_mail', $email])->
                    one() === NULL) {
                        $this->addError($attribute, "Not exists: " . $email);
                        return;
                }
            }
        }
    }
    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);
        
        // if it is new record - just run old script. Else - check what attributes
        // were changed - if login or password need some additional actions 
        // (remove old login from htpasswd, reset password in htpasswd)
        if($insert){
            // run update.. script
        } else {
            if(isset($changedAttributes['login']) || isset($changedAttributes['passwd'])){
                // need to update htpasswd file
                file_put_contents('/opt/lampp/htdocs/itmdev/runtime/log.log', print_r($changedAttributes, true));
            }
        }
    }
    public function getfullName(){
        return $this->name_f . " " . $this->name_i . " " . $this->name_o;
    }
}
