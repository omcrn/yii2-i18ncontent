<?php

use yii\db\Migration;

/**
 * Class m180919_074811_alter_table_article_category_add_column_published_at
 */
class m180919_074811_alter_table_article_category_add_column_published_at extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%article_category}}','published_at',$this->integer(11));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%article_category}}','published_at');
    }
}
