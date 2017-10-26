<?php

namespace app\models\mail;

use Yii;
use app\helpers\EmailHelpers;
use app\validators\EmailListValidator;
use app\helpers\ExternalCommander;
use yii\base\ErrorException;

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
    private $_domain;
    
    public function getDomain(){
        return $this->_domain;
    }
    public function setDomain($value){
        $this->_domain = $value;
    }
    
    public function scenarios() {
        $scenarios = parent::scenarios();
        
        // Regular email
        $scenarios['userEmail'] = ['name_f', 'name_i', 'name_o', 'guid', 'login', 
                'E_mail', 'passwd', 'spam_f', 'greylist', 'visible_mail', 'domain', 'aliases'];
        
        // Service email
        $scenarios['serviceEmail'] = ['name_f', 'login', 'E_mail','passwd', 'spam_f', 
                'greylist','domain'];
        
        // Alias for existing email
        $scenarios['alias'] = ['E_mail','aliases','domain', 'name_i', 'name_o', 'name_f','spam_f','greylist'];
        
        return $scenarios;
    }


    /**
     * Fill in E_mail field based on Domain and login properties
     * @return boolean if successful
     */
    public function GenerateE_mailField(){
        if(count($this->login)>0 && count($this->_domain)>0) {
            $this->E_mail = $this->login . "@" . $this->_domain;
            return TRUE;
        }
        return false;
    }
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
            [['date_cr', 'domain'], 'safe'],
            [['IsLocal', 'visible_mail'], 'integer'],
            [['IsEnabled', 'IsDismiss'], 'string'],
            [['name_f'], 'string', 'max' => 64],
            [['name_i', 'name_o', 'login', 'passwd', 'tel', 'komn', 'otdel'], 'string', 'max' => 20],
            [['guid'], 'string', 'max' => 36],
            [['E_mail'], 'string', 'max' => 40],
            [['E_mail'], 'unique'],
            [['E_mail', 'name_f'], 'required'],
            [['login'], 'unique'],
            [['ip'], 'string', 'max' => 200],
            [['spam_f', 'greylist'], 'string', 'max' => 1],
            ['aliases', EmailListValidator::className()],
            [['aliases'], 'required', 'on' => 'alias'], // For scenario 'alias'
            ['domain', 'checkDomain'],
            [['name_f', 'name_i', 'name_o', 'guid', 'login', 'E_mail', 
              'passwd', 'spam_f', 'greylist', 'visible_mail'],'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name_f' => 'Last name',
            'name_i' => 'First name',
            'name_o' => 'Middle name',
            'guid' => 'Guid',
            'login' => 'Login',
            'E_mail' => 'E Mail',
            'passwd' => 'Password',
            'ip' => 'Ip',
            'date_cr' => 'Date Cr',
            'spam_f' => 'Spam Filter',
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
     * Validation for aliases filed
     * 
     * Deny set up alias on alias
     * 
     * @param type $attribute
     * @param type $params
     * @return void
     */
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
    /**
     * Is our domain
     * @return boolean
     */
    public function checkDomain($attribute) {
        if(isset($this->$attribute)) {
            if(! EmailHelpers::IsOurDomain($this->$attribute)) {
                $this->addError($attribute, 'Bad domain: ' . $this->$attribute);
            }
        }
        else { 
            $this->addError($attribute, 'Enter domain');
        }
    }
    
    public function checkEmailByLogin($attribute) {
        if(isset($this->$attribute)) {
            if(! EmailHelpers::IsOurDomain($this->$attribute)) {
                $this->addError($attribute, 'Bad domain: ' . $this->$attribute);
            }
        }
        else { 
            $this->addError($attribute, 'Enter domain');
        }
    }
    /** Is alias record
     * 
     * @return boolean
     */
    public function getIsAlias() {
        if($this->login === null || $this->login == '') {return TRUE;}
        else {return False; }
    }
    /**
     * Converts regular e-mail record to alias
     * 
     * It nulls login and password fields and reconfigures postfix
     * @return type nothing
     * @throws ErrorException
     */
    public function ConvertToAlias() {
        // Validate aliases
        $validator = new EmailListValidator();
        if(strlen($this->aliases) === 0 || !$validator->validate($this->aliases)) {
            throw new ErrorException('Aliases property not set or has invalid value');
        }

        $login = $this->login;
        $this->login = '';
        $this->passwd = '';
        if(!$this->save(false, ['login', 'passwd', 'aliases'])) {
            throw new ErrorException('Model not saved');
        }
        
        return "call ExternalCommander::RemoveEmailUser($login);";
    }
    /**
     * Add additional aliases to existing
     * @param string $addition new aliases
     * @throws ErrorException
     */
    public function AddAliases($addition) {
        if(strlen($addition)== 0) {throw new ErrorException('Bad argument'); }
        $validator = new EmailListValidator();
        
        if(! $validator->validate($addition)) {
            throw new ErrorException('Bad format');
        }
        if(strlen($this->aliases) == 0) {$this->aliases = $addition; }
        else { $this->aliases .= ',' . $addition; }
    }
    public function AliasesCount() {
        if(strlen($this->aliases) == 0) { return 0; }
        
        return count(split("'", $this->aliases));
    }
    /**
     * Return aliases with single destination referenced to this record
     * 
     * @return MailRecord[]
     */
    public function GetDependings() {
        return self::GetDependingRecords($this->id);
    }
    
    /**
     * Return records type of which is alias and they have only one destination - $id
     * @param integer $id
     * @return MailRecord[]
     */
    public static function GetDependingRecords($id) {
        $model = MailRecord::findOne($id);

        $sql = "SELECT * FROM mail WHERE aliases like :email and login=''";
        $depended = MailRecord::findBySql($sql, [':email' => $model->E_mail])->all();

        $ret = array();
        // Find what records has single alias
        foreach($depended as $item) {
            if($item->AliasesCount() == 1) { $ret[] = $item; }
        }
        
        return $ret;
    }
}
