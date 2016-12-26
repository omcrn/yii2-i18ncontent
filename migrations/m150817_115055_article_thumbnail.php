<?php

use yii\db\Schema;
use yii\db\Migration;

class m150817_115055_article_thumbnail extends Migration
{

    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->addColumn('{{%article}}', 'thumbnail_base_url', Schema::TYPE_STRING . '(1024)');
        $this->addColumn('{{%article}}', 'thumbnail_path', Schema::TYPE_STRING . '(1024)');
    }

    public function safeDown()
    {
        $this->dropColumn('{{%article}}', 'thumbnail_base_url');
        $this->dropColumn('{{%article}}', 'thumbnail_path');
    }

}
