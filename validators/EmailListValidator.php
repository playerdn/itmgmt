<?php

namespace app\validators;

use yii\validators\Validator;
use app\helpers\EmailHelpers;
use app\models\mail\MailRecord;

class EmailListValidator extends Validator {
    public function validateAttribute($model, $attribute) {
        if (($valRet = $this->myValidate($model->$attribute)) != NULL) {
            $this->addError($model, $attribute, $valRet);
        }
    }
    public function validate($value, &$error = null) {
        if (($valRet = $this->myValidate($value)) != NULL) {
            $error = $valRet;
            return FALSE;
        }
        return TRUE;
    }
    
    public function myValidate($value) {
        $emails = split(",", $value);
        $validator = new \yii\validators\EmailValidator();

        foreach($emails as $email) {
            $email = trim($email);
            if(!$validator->validate($email)) {
                return 'Not email: "' . $email . '"';
            }
            
            if(EmailHelpers::IsOurEmail($email)){
                // Check existing
                $rec = MailRecord::find()->
                    filterWhere(['like', 'E_mail', $email])->
                    one();
                if( $rec === NULL) {
                    return "Not exists: " . $email;
                } else {
                    if($rec->login == NULL ||
                        trim($rec->login) == '') {
                        return "Alias not allowed: " . $email;
                    }
                }
            }
        }
        
        return NULL;
    }
}