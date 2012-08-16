<?php

class m120410_144815_addFirstAdmin extends CDbMigration
{

    public function up()
    {
        $this->execute("CREATE TABLE IF NOT EXISTS `".Yii::app()->db->tablePrefix."admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `a_login` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `a_password` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `super_admin` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;");
        $this->execute("CREATE TABLE IF NOT EXISTS `".Yii::app()->db->tablePrefix."admin_activity_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `ip` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '127.0.0.1',
  `admin_name` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `action` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'unknown',
  `entity` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `message` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `additional` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");
        $this->execute("INSERT INTO `".Yii::app()->db->tablePrefix."admin` VALUES(1, 'admin', 'e6c10ba2c8c5ec1961035486ba3781b5cf75ed02', 'Admin', 1);");
    }

    public function down()
    {
        echo "m120410_144815_addFirstAdmin does not support migration down.\n";
        return false;
    }

    /*
      // Use safeUp/safeDown to do migration with transaction
      public function safeUp()
      {
      }

      public function safeDown()
      {
      }
     */
}