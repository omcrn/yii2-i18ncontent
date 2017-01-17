<?php

use yii\db\Migration;



class m170113_101554_alter_widget_text_translation extends Migration
{
    public function up()
    {
        $this->alterColumn(\centigen\i18ncontent\models\WidgetTextTranslation::tableName(), 'title', 'string NULL');
        $this->alterColumn(\centigen\i18ncontent\models\WidgetTextTranslation::tableName(), 'body', 'text NULL');
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
