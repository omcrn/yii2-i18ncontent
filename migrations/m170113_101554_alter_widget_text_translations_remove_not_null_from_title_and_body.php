<?php

use yii\db\Migration;

class m170113_101554_alter_widget_text_translations_remove_not_null_from_title_and_body extends Migration
{
    public function up()
    {
        $this->alterColumn('widget_text_translations', 'title', 'string NULL');
        $this->alterColumn('widget_text_translations', 'body', 'text NULL');
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
