<?php

use yii\db\Schema;
use yii\db\Migration;

class m150812_101506_widget_text extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%widget_text}}', [
            'id' => Schema::TYPE_PK,
            'key' => Schema::TYPE_STRING . '(255) NOT NULL',
            'status' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 0',
            'created_at' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
        ], $tableOptions);

        $this->createIndex('idx_widget_text_key', '{{%widget_text}}', 'key');

        $this->createTable(\centigen\i18ncontent\models\WidgetTextTranslation::tableName(), [
            'id' => $this->primaryKey(11),
            'widget_text_id' => $this->integer(11)->notNull(),
            'title' => $this->string(512)->notNull(),
            'body' => $this->text()->notNull(),
            'locale' => $this->string(15)->notNull()
        ], $tableOptions);

        $this->insert('{{%widget_text}}', [
            'key' => 'backend_welcome',
            'status' => 1,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        $this->insert('{{%widget_text}}', [
            'key' => 'frontend_welcome',
            'status' => 1,
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        $this->insert(\centigen\i18ncontent\models\WidgetTextTranslation::tableName(), [
            'widget_text_id' => 1,
            'title' => 'English title',
            'body' => 'Welcome to "' . Yii::$app->name . '" backend',
            'locale' => 'en-US'
        ]);
        $this->insert(\centigen\i18ncontent\models\WidgetTextTranslation::tableName(), [
            'widget_text_id' => 1,
            'title' => 'Russian title',
            'body' => 'добро пожаловать в админку "' . Yii::$app->name . '"',
            'locale' => 'ru-RU'
        ]);

        $this->insert(\centigen\i18ncontent\models\WidgetTextTranslation::tableName(), [
            'widget_text_id' => 2,
            'title' => 'English title',
            'body' => 'Welcome to "' . Yii::$app->name . '"',
            'locale' => 'en-US'
        ]);

        $this->insert(\centigen\i18ncontent\models\WidgetTextTranslation::tableName(), [
            'widget_text_id' => 2,
            'title' => 'Russian title',
            'body' => 'добро пожаловать в "' . Yii::$app->name . '"',
            'locale' => 'ru-RU'
        ]);

        if ($this->db->driverName === 'mysql') {
            $this->createIndex('IDX_widget_text_translation_widget_text_id', \centigen\i18ncontent\models\WidgetTextTranslation::tableName(), 'widget_text_id');
            $this->addForeignKey('FK_widget_text_translation_widget_text_id', \centigen\i18ncontent\models\WidgetTextTranslation::tableName(), 'widget_text_id', '{{%widget_text}}', 'id', 'cascade', 'cascade');
        }
    }

    public function safeDown()
    {
        $this->dropForeignKey('FK_widget_text_translation_widget_text_id', \centigen\i18ncontent\models\WidgetTextTranslation::tableName());
        $this->dropTable(\centigen\i18ncontent\models\WidgetTextTranslation::tableName());
        $this->dropTable('{{%widget_text}}');
    }
}
