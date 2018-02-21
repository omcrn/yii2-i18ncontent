<?php

use yii\db\Migration;

/**
 * Handles adding short_description to table `page_transaction`.
 */
class m180221_085308_add_short_description_column_to_page_translation_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%page_translation}}', 'short_description', $this->text());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('{{%page_translation}}', 'short_description');
    }
}
