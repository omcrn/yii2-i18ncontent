<?php

use yii\db\Migration;



class m170124_101554_alter_article_translation extends Migration
{
    public function up()
    {
        $this->addColumn(\centigen\i18ncontent\models\ArticleTranslation::tableName(),'short_description','TEXT');
    }

    public function down()
    {
        return true;
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
