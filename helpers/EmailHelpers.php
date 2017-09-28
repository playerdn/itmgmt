<?php

namespace app\helpers;

use app\models\mail\MailRecord;

class EmailHelpers {
    private static $ourDomains = ['ungg.org', 'ungg.net', 'yuzh-gaz.donetsk.ua', 
                        'ungg.donetsk.ua', 'ungg2.donetsk.ua'];
    
    /**
     * Generate description for e-mail by model fields
     * 
     * @param app\models\mail\MailSearchModel $model
     * @return string Email description
     */
    public static function CreateEmailDescription($model) {
        $n = mb_substr($model->name_i, 0, 1);
        $o = mb_substr($model->name_o, 0, 1);
        if(strlen($n)>0 && strlen($o)) {
            return $model->name_f . " " . $model->name_i . " ".
                $model->name_o;
        } else { return $model->name_f; }
    }
    
    /**
     * Return visual representation (glyphicon) for e-mail attributes
     * 
     * @param app\models\mail\MailSearchModel $model
     * @param string $prop Model property
     * @return string HTML glyphicon
     */
    public static function AttributeVisualization($model, $prop) {
        if($model->$prop) {
            return '<i class="glyphicon glyphicon-ok"></i>';
        } else { return ''; }
    }
    
    /**
     * Return array of MailRecords which from send copy od incoming mail to email 
     * passed in $model arg
     * 
     * @param app\models\mail\MailSearchModel $model
     * @return app\models\mail\MailRecord[] Forward sources for $model
     */
    public static function GetForwarderForEmail($model) {
        return MailRecord::find()->filterWhere(['like', 'aliases', $model->E_mail])->all();
    }
    
    /**
     * Check is email located on ours servers
     * 
     * @param string $email
     * @return boolean
     */
    public static function IsOurEmail($email){
        $emailLow = strtolower($email);
        $dom = substr($emailLow, strrpos($emailLow, '@')+1);
        
        return in_array($dom, self::$ourDomains);
    }
}
