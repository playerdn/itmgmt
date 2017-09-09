<?php

use yii\db\Migration;

class m170907_120549_create_trigger_for_log extends Migration
{
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $sql = 'CREATE TRIGGER `SetDatetimeNow` BEFORE INSERT ON  `log` FOR EACH ROW SET NEW.DATENTIME = NOW()';
        $this->execute($sql);
    }

    public function down()
    {
        $sql = "DROP TRIGGER IF EXISTS `SetDatetimeNow`;";
        $this->execute($sql);    
    }
}
