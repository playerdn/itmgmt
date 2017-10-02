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
    
    public static function SuggestLogin($lname, $fname='', $mname='') {
        $lname = trim(preg_replace('/[^a-zA-Zа-яА-Я0-9]/ui', '',$lname));
        $fname = trim(preg_replace('/[^a-zA-Zа-яА-Я0-9]/ui', '',$fname));
        $mname = trim(preg_replace('/[^a-zA-Zа-яА-Я0-9]/ui', '',$mname));
        
        $lnameEn = strtolower(self::get_in_translate_to_en($lname));
        
        $o = MailRecord::find()->where(['=', 'login', $lnameEn])->orWhere(['like','E_mail', $lnameEn])->one();
        if($o == null) {return $lnameEn; }
        
        if(strlen($fname)>0 && strlen($mname)>0) {
            $fnameEn = mb_substr(strtolower(self::get_in_translate_to_en($fname)),0,1);
            $mnameEn = mb_substr(strtolower(self::get_in_translate_to_en($mname)),0,1);
            $login = $lnameEn . "_$fnameEn$mnameEn";
            $o = MailRecord::find()->where(['=', 'login', $login])->orWhere(['like','E_mail', $login])->one();
            if($o == null) {return $login; }
        }
    }
    
    public static function get_in_translate_to_en($string, $gost=false)
    {
	if($gost)
	{
		$replace = array("А"=>"A","а"=>"a","Б"=>"B","б"=>"b","В"=>"V","в"=>"v","Г"=>"G","г"=>"g","Д"=>"D","д"=>"d",
                "Е"=>"E","е"=>"e","Ё"=>"E","ё"=>"e","Ж"=>"Zh","ж"=>"zh","З"=>"Z","з"=>"z","И"=>"I","и"=>"i",
                "Й"=>"I","й"=>"i","К"=>"K","к"=>"k","Л"=>"L","л"=>"l","М"=>"M","м"=>"m","Н"=>"N","н"=>"n","О"=>"O","о"=>"o",
                "П"=>"P","п"=>"p","Р"=>"R","р"=>"r","С"=>"S","с"=>"s","Т"=>"T","т"=>"t","У"=>"U","у"=>"u","Ф"=>"F","ф"=>"f",
                "Х"=>"Kh","х"=>"kh","Ц"=>"Tc","ц"=>"tc","Ч"=>"Ch","ч"=>"ch","Ш"=>"Sh","ш"=>"sh","Щ"=>"Shch","щ"=>"shch",
                "Ы"=>"Y","ы"=>"y","Э"=>"E","э"=>"e","Ю"=>"Iu","ю"=>"iu","Я"=>"Ia","я"=>"ia","ъ"=>"","ь"=>"");
	}
	else
	{
		$arStrES = array("ае","уе","ое","ые","ие","эе","яе","юе","ёе","ее","ье","ъе","ый","ий");
		$arStrOS = array("аё","уё","оё","ыё","иё","эё","яё","юё","ёё","её","ьё","ъё","ый","ий");        
		$arStrRS = array("а$","у$","о$","ы$","и$","э$","я$","ю$","ё$","е$","ь$","ъ$","@","@");
                    
		$replace = array("А"=>"A","а"=>"a","Б"=>"B","б"=>"b","В"=>"V","в"=>"v","Г"=>"G","г"=>"g","Д"=>"D","д"=>"d",
                "Е"=>"Ye","е"=>"e","Ё"=>"Ye","ё"=>"e","Ж"=>"Zh","ж"=>"zh","З"=>"Z","з"=>"z","И"=>"I","и"=>"i",
                "Й"=>"Y","й"=>"y","К"=>"K","к"=>"k","Л"=>"L","л"=>"l","М"=>"M","м"=>"m","Н"=>"N","н"=>"n",
                "О"=>"O","о"=>"o","П"=>"P","п"=>"p","Р"=>"R","р"=>"r","С"=>"S","с"=>"s","Т"=>"T","т"=>"t",
                "У"=>"U","у"=>"u","Ф"=>"F","ф"=>"f","Х"=>"Kh","х"=>"kh","Ц"=>"Ts","ц"=>"ts","Ч"=>"Ch","ч"=>"ch",
                "Ш"=>"Sh","ш"=>"sh","Щ"=>"Shch","щ"=>"shch","Ъ"=>"","ъ"=>"","Ы"=>"Y","ы"=>"y","Ь"=>"","ь"=>"",
                "Э"=>"E","э"=>"e","Ю"=>"Yu","ю"=>"yu","Я"=>"Ya","я"=>"ya","@"=>"y","$"=>"ye");
                
		$string = str_replace($arStrES, $arStrRS, $string);
		$string = str_replace($arStrOS, $arStrRS, $string);
	}
        
	return iconv("UTF-8","UTF-8//IGNORE",strtr($string,$replace));
    }
}
