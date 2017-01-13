<?php

use yii\db\Schema;
use yii\db\Migration;

class m150817_115055_article_thumbnail extends Migration
{

    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->addColumn(\centigen\i18ncontent\models\Article::tableName(), 'thumbnail_base_url', Schema::TYPE_STRING . '(1024)');
        $this->addColumn(\centigen\i18ncontent\models\Article::tableName(), 'thumbnail_path', Schema::TYPE_STRING . '(1024)');
    }

    public function safeDown()
    {
        $this->dropColumn(\centigen\i18ncontent\models\Article::tableName(), 'thumbnail_base_url');
        $this->dropColumn(\centigen\i18ncontent\models\Article::tableName(), 'thumbnail_path');
    }

}
