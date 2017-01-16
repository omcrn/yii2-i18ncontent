<?php

use centigen\i18ncontent\models\WidgetCarousel;
use yii\db\Schema;
use yii\db\Migration;

class m150812_102053_widget_carousel extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%widget_carousel}}', [
            'id' => Schema::TYPE_PK,
            'key' => Schema::TYPE_STRING . '(1024) NOT NULL',
            'status' => Schema::TYPE_SMALLINT . ' DEFAULT 0'
        ], $tableOptions);

        $this->insert('{{%widget_carousel}}', [
            'id' => 1,
            'key' => 'index',
            'status' => WidgetCarousel::STATUS_ACTIVE
        ]);


        $this->createTable(\centigen\i18ncontent\models\WidgetCarouselItem::tableName(), [
            'id' => Schema::TYPE_PK,
            'carousel_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'base_url' => Schema::TYPE_STRING . '(1024)',
            'path' => Schema::TYPE_STRING . '(1024)',
            'type' => Schema::TYPE_STRING . '(45)',
            'url' => Schema::TYPE_STRING . '(1024) ',
            'status' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 0',
            'order' => Schema::TYPE_INTEGER . ' DEFAULT 0',
            'created_at' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
        ], $tableOptions);

        $this->insert(\centigen\i18ncontent\models\WidgetCarouselItem::tableName(), [
            'carousel_id' => 1,
            'base_url' => '/',
            'path' => 'img/yii2-starter-kit.gif',
            'type' => 'image/gif',
            'url' => '/',
            'status' => 1
        ]);

        if ($this->db->driverName === 'mysql') {
            $this->createIndex('idx_carousel_id', \centigen\i18ncontent\models\WidgetCarouselItem::tableName(), 'carousel_id');
            $this->addForeignKey('fk_item_carousel', \centigen\i18ncontent\models\WidgetCarouselItem::tableName(), 'carousel_id', '{{%widget_carousel}}', 'id', 'cascade', 'cascade');
        }

        $this->createTable(\centigen\i18ncontent\models\WidgetCarouselItemTranslation::tableName(), [
            'id' => $this->primaryKey(11),
            'widget_carousel_item_id' => $this->integer(11)->notNull(),
            'locale' => $this->string(15)->notNull(),
            'caption' => $this->string(1024)
        ], $tableOptions);

        if ($this->db->driverName === 'mysql') {
            $this->createIndex('IDX_widget_carousel_item_translation_widget_carousel_item_id',
                \centigen\i18ncontent\models\WidgetCarouselItemTranslation::tableName(), 'widget_carousel_item_id');
            $this->addForeignKey('FK_widget_carousel_item_translation_widget_carousel_item_id',
                \centigen\i18ncontent\models\WidgetCarouselItemTranslation::tableName(), 'widget_carousel_item_id',
                \centigen\i18ncontent\models\WidgetCarouselItem::tableName(), 'id', 'cascade', 'cascade');
        }
    }


    public function safeDown()
    {
        $this->dropForeignKey('FK_widget_carousel_item_translation_widget_carousel_item_id',
            \centigen\i18ncontent\models\WidgetCarouselItemTranslation::tableName());
        $this->dropTable(\centigen\i18ncontent\models\WidgetCarouselItemTranslation::tableName());
        $this->dropForeignKey('fk_item_carousel', \centigen\i18ncontent\models\WidgetCarouselItem::tableName());
        $this->dropTable(\centigen\i18ncontent\models\WidgetCarouselItem::tableName());
        $this->dropTable('{{%widget_carousel}}');
    }
}
