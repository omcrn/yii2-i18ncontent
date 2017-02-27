<?php

use yii\db\Migration;

class m170203_101803_alter_table_widget_menu_add_column_base_options extends Migration
{
    public function up()
    {
        $this->addColumn(\centigen\i18ncontent\models\WidgetMenu::tableName(),'base_options','TEXT');
    }

    public function down()
    {
        echo "m170203_101803_alter_table_widget_menu_add_collumn_baseOptions cannot be reverted.\n";

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
