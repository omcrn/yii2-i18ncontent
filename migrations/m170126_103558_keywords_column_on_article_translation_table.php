<?php

use yii\db\Migration;

class m170126_103558_keywords_column_on_article_translation_table extends Migration
{
    public function up()
    {
        $this->addColumn(\centigen\i18ncontent\models\ArticleTranslation::tableName(), 'keywords', \yii\db\Schema::TYPE_STRING."(256)");
    }

    public function down()
    {
        $this->dropColumn(\centigen\i18ncontent\models\ArticleTranslation::tableName(), 'keywords');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
