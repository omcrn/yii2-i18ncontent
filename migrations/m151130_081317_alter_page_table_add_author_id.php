<?php

use yii\db\Migration;

class m151130_081317_alter_page_table_add_author_id extends Migration
{
    public function up()
    {
        $this->addColumn(\centigen\i18ncontent\models\Page::tableName(), 'author_id', $this->integer());
        $this->addColumn(\centigen\i18ncontent\models\Page::tableName(), 'updater_id', $this->integer());

        $this->createIndex('FK_page_author_id', \centigen\i18ncontent\models\Page::tableName(), 'author_id');
        $this->addForeignKey('FK_page_author_id', \centigen\i18ncontent\models\Page::tableName(), 'author_id', '{{%user}}', 'id', 'cascade', 'cascade');

        $this->createIndex('FK_page_updater_id', \centigen\i18ncontent\models\Page::tableName(), 'updater_id');
        $this->addForeignKey('FK_page_updater_id', \centigen\i18ncontent\models\Page::tableName(), 'updater_id', '{{%user}}', 'id', 'set null', 'cascade');
    }

    public function down()
    {
        $this->dropIndex('FK_page_author_id', \centigen\i18ncontent\models\Page::tableName());
        $this->dropForeignKey('FK_page_updater_id', \centigen\i18ncontent\models\Page::tableName());

        $this->dropIndex('FK_page_author_id', \centigen\i18ncontent\models\Page::tableName());
        $this->dropForeignKey('FK_page_updater_id', \centigen\i18ncontent\models\Page::tableName());

        $this->dropColumn(\centigen\i18ncontent\models\Page::tableName(), 'author_id');
        $this->dropColumn(\centigen\i18ncontent\models\Page::tableName(), 'updater_id');
    }
}
