<?php

use yii\db\Migration;
use yii\db\Schema;

class m151130_081317_alter_article_translate_table_add_meta_fields extends Migration
{
    public function up()
    {
        $this->addColumn('{{%article_translations}}', 'meta_title', $this->string(512)->null());
        $this->addColumn('{{%article_translations}}', 'meta_description', $this->string(512)->null());
        $this->addColumn('{{%article_translations}}', 'meta_keywords', $this->string(512)->null());
    }

    public function down()
    {
        $this->dropColumn('{{%article_translations}}', 'meta_title');
        $this->dropColumn('{{%article_translations}}', 'meta_description');
        $this->dropColumn('{{%article_translations}}', 'meta_keywords');
    }
}
