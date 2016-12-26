<?php

use yii\db\Schema;
use yii\db\Migration;

class m150814_113326_article extends Migration
{

    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%article_category}}', [
            'id' => Schema::TYPE_PK,
            'slug' => Schema::TYPE_STRING . '(1024) NOT NULL',
            'view' => Schema::TYPE_STRING . '(1024)',
            'parent_id' => Schema::TYPE_INTEGER,
            'status' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 0',
            'created_at' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
        ], $tableOptions);

        $this->createTable('{{%article}}', [
            'id' => Schema::TYPE_PK,
            'slug' => Schema::TYPE_STRING . '(1024) NOT NULL',
            'view' => Schema::TYPE_STRING . '(255)',
            'category_id' => Schema::TYPE_INTEGER,
            'author_id' => Schema::TYPE_INTEGER,
            'updater_id' => Schema::TYPE_INTEGER,
            'status' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 0',
            'published_at' => Schema::TYPE_INTEGER,
            'created_at' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
        ], $tableOptions);

        $this->createIndex('idx_article_author_id', '{{%article}}', 'author_id');
        $this->addForeignKey('fk_article_author', '{{%article}}', 'author_id', '{{%user}}', 'id', 'cascade', 'cascade');

        $this->createIndex('idx_article_updater_id', '{{%article}}', 'updater_id');
        $this->addForeignKey('fk_article_updater', '{{%article}}', 'updater_id', '{{%user}}', 'id', 'set null', 'cascade');

        $this->createIndex('idx_category_id', '{{%article}}', 'category_id');
        $this->addForeignKey('fk_article_category', '{{%article}}', 'category_id', '{{%article_category}}', 'id');

        $this->createIndex('idx_parent_id', '{{%article_category}}', 'parent_id');
        $this->addForeignKey('fk_article_category_section', '{{%article_category}}', 'parent_id', '{{%article_category}}', 'id', 'cascade', 'cascade');

        $this->createTable('{{%article_category_translations}}', [
            'id' => $this->primaryKey(11),
            'article_category_id' => $this->integer(11)->notNull(),
            'locale' => $this->string(11)->notNull(),
            'title' => $this->string(512)->notNull(),
            'body' => $this->text()
        ], $tableOptions);

        $this->createTable('{{%article_translations}}', [
            'id' => $this->primaryKey(11),
            'article_id' => $this->integer(11)->notNull(),
            'locale' => $this->string(15)->notNull(),
            'title' => $this->string(512)->notNull(),
            'body' => $this->text()->notNull()
        ]);

        $this->createIndex('IDX_article_category_translations_article_category_id', '{{%article_category_translations}}', 'article_category_id');
        $this->addForeignKey('FK_article_category_translations_article_category_id', '{{%article_category_translations}}', 'article_category_id', '{{%article_category}}', 'id', 'cascade', 'cascade');
        $this->createIndex('IDX_article_translations_article_id', '{{%article_translations}}', 'article_id');
        $this->addForeignKey('FK_article_translations_article_id', '{{%article_translations}}', 'article_id', '{{%article}}', 'id', 'cascade', 'cascade');

        $this->insert('{{%article_category}}', [
            'slug' => 'news',
            'status' => 1,
            'created_at' => time(),
            'updated_at' => time()
        ]);

        $this->insert('{{%article_category_translations}}', [
            'article_category_id' => 1,
            'locale' => 'en_US',
            'title' => 'News'
        ]);

        $this->insert('{{%article_category_translations}}', [
            'article_category_id' => 1,
            'locale' => 'ru_RU',
            'title' => 'новости'
        ]);

    }

    public function safeDown()
    {
        $this->dropForeignKey('FK_article_category_translations_article_category_id', '{{%article_category_translations}}');
        $this->dropForeignKey('FK_article_translations_article_id', '{{%article_translations}}');
        $this->dropForeignKey('fk_article_author', '{{%article}}');
        $this->dropForeignKey('fk_article_updater', '{{%article}}');
        $this->dropForeignKey('fk_article_category', '{{%article}}');
        $this->dropForeignKey('fk_article_category_section', '{{%article_category}}');

        $this->dropTable('{{%article_translations}}');
        $this->dropTable('{{%article_category_translations}}');
        $this->dropTable('{{%article}}');
        $this->dropTable('{{%article_category}}');
    }

}
