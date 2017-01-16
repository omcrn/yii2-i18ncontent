<?php

use yii\db\Migration;

class m170113_101554_alter_widget_text_translation_remove_not_null_from_title_and_body extends Migration
{
    public function up()
    {
        $this->alterColumn(\centigen\i18ncontent\models\WidgetTextTranslation::tableName(), 'title', 'string NULL');
        $this->alterColumn(\centigen\i18ncontent\models\WidgetTextTranslation::tableName(), 'body', 'text NULL');
    }

    public function down()
    {
        return false;
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
