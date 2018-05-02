<?php

use yii\db\Migration;

/**
 * Class m180502_132704_alter_table_article_add_column_show_published_at
 */
class m180502_132704_alter_table_article_add_column_show_published_at extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("{{%article}}",'show_published_at',$this->boolean()->null()->after('published_at'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn("{{%article}}",'show_published_at');
    }
}
