<?php

use yii\db\Migration;

/**
 * Class m180416_120424_add_path_mobile_column_to_widget_carousel_item
 */
class m180416_120424_add_path_mobile_column_to_widget_carousel_item extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('{{%widget_carousel_item}}', 'path_mobile', $this->text());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn( '{{%widget_carousel_item}}', 'path_mobile');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180416_120424_add_path_mobile_column_to_widget_carousel_item cannot be reverted.\n";

        return false;
    }
    */
}
