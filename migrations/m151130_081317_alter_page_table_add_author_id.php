<?php

use yii\db\Migration;

class m151130_081317_alter_page_table_add_author_id extends Migration
{
    public function up()
    {
        $this->addColumn('{{%page}}', 'author_id', $this->integer());
        $this->addColumn('{{%page}}', 'updater_id', $this->integer());

        $this->createIndex('FK_page_author_id', '{{%page}}', 'author_id');
        $this->addForeignKey('FK_page_author_id', '{{%page}}', 'author_id', '{{%user}}', 'id', 'cascade', 'cascade');

        $this->createIndex('FK_page_updater_id', '{{%page}}', 'updater_id');
        $this->addForeignKey('FK_page_updater_id', '{{%page}}', 'updater_id', '{{%user}}', 'id', 'set null', 'cascade');
    }

    public function down()
    {
        $this->dropIndex('FK_page_author_id', '{{%page}}');
        $this->dropForeignKey('FK_page_updater_id', '{{%page}}');

        $this->dropIndex('FK_page_author_id', '{{%page}}');
        $this->dropForeignKey('FK_page_updater_id', '{{%page}}');

        $this->dropColumn('{{%page}}', 'author_id');
        $this->dropColumn('{{%page}}', 'updater_id');
    }
}
