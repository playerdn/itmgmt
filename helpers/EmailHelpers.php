<?php

namespace app\helpers;

use app\models\mail\MailRecord;

class EmailHelpers {
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
     * Check is email located on ours servers (check domain part of emails)
     * 
     * @param string $email
     * @return boolean
     */
    public static function IsOurEmail($email){
        $emailLow = strtolower($email);
        $dom = substr($emailLow, strrpos($emailLow, '@')+1);
        
        return self::IsOurDomain($dom);
    }
    
    /**
     * Check domain our or not
     * 
     * @param string $dom
     * @return boolean true if ours
     */
    public static function IsOurDomain($dom) {
        return in_array(strtolower($dom), EnterpriseDomains::getDomains());
    }
    
    /**
     * Generates email login based on user name. 
     * Checks that login not match with user part (before @) existing emails
     * 
     * @param string $lname Last name
     * @param string $fname First name
     * @param string $mname Middle name
     * @return string
     */
    public static function SuggestLogin($lname, $fname='', $mname='') {
        $lname = trim(preg_replace('/[^a-zA-Zа-яА-Я0-9]/ui', '',$lname));
        $fname = trim(preg_replace('/[^a-zA-Zа-яА-Я0-9]/ui', '',$fname));
        $mname = trim(preg_replace('/[^a-zA-Zа-яА-Я0-9]/ui', '',$mname));
        
        $lnameEn = strtolower(self::get_in_translate_to_en($lname));
        
        // Try pure surname
        if(self::IsUsernameFree($lnameEn)) { return $lnameEn; }
        
        if(strlen($fname)>0) {
            $fnameEn = mb_substr(strtolower(self::get_in_translate_to_en($fname)),0,1);
        }
        
        if(strlen($mname)>0) {
            $mnameEn = mb_substr(strtolower(self::get_in_translate_to_en($mname)),0,1);
        }
        
        // Try format surname with initials
        if(strlen($fname)>0 && strlen($mname)>0) {
            $login = $lnameEn . "_$fnameEn$mnameEn";
            if(self::IsUsernameFree($login)) { return $login; }
        } else if (strlen($fname) > 0) {
            // Try surname and first letter in name
            $login = $lnameEn . "_$fnameEn";
            if(self::IsUsernameFree($login)) { return $login; }
            else {
                // Surname and full name
                $fnameEn = strtolower(self::get_in_translate_to_en($fname));
                $login = $lnameEn . "_$fnameEn";
                if(self::IsUsernameFree($login)) { return $login; }
            }
        }
        
        return '';
    }
    
    /**
     * Transliterate russian names to latin
     * 
     * @param string $string string for transliteration
     * @param bool $gost Use GOST rules
     * @return string transliterated string
     */
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
    
    /**
     * Check given username is available for use
     * 
     * @param string $uname
     * @return boolean true if free
     */
    public static function IsUsernameFree($uname) {
        $o = MailRecord::find()->where(['=', 'login', $uname])->orWhere(['like','E_mail', $uname . '@'])->one();
        if($o == null) {return true; }
        else { return false;}
    }
    public static function GetOurDomains() {
        return EnterpriseDomains::getDomains();
    }
    public static function generatePassword($number) {
        $arr = array('a','b','c','d','e','f',
                     'g','h','i','j','k','l',
                    'm','n','o','p','r','s',
                    't','u','v','x','y','z',
                    'A','B','C','D','E','F',
                    'G','H','I','J','K','L',
                    'M','N','O','P','R','S',
                    'T','U','V','X','Y','Z',
                    '1','2','3','4','5','6',
                    '7','8','9','0');

        // Генерируем пароль
        $pass = "";

        for($i = 0; $i < $number; $i++)
        {
            // Вычисляем случайный индекс массива
            $index = rand(0, count($arr) - 1);
            $pass .= $arr[$index];
        }

        return $pass;
    }
}
