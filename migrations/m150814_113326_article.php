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

        $this->createTable(\centigen\i18ncontent\models\ArticleCategory::tableName(), [
            'id' => Schema::TYPE_PK,
            'slug' => Schema::TYPE_STRING . '(1024) NOT NULL',
            'view' => Schema::TYPE_STRING . '(1024)',
            'parent_id' => Schema::TYPE_INTEGER,
            'status' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 0',
            'created_at' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
        ], $tableOptions);

        $this->createTable(\centigen\i18ncontent\models\Article::tableName(), [
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

        $this->createIndex('idx_article_author_id', \centigen\i18ncontent\models\Article::tableName(), 'author_id');
        $this->addForeignKey('fk_article_author', \centigen\i18ncontent\models\Article::tableName(), 'author_id', '{{%user}}', 'id', 'cascade', 'cascade');

        $this->createIndex('idx_article_updater_id', \centigen\i18ncontent\models\Article::tableName(), 'updater_id');
        $this->addForeignKey('fk_article_updater', \centigen\i18ncontent\models\Article::tableName(), 'updater_id', '{{%user}}', 'id', 'set null', 'cascade');

        $this->createIndex('idx_category_id', \centigen\i18ncontent\models\Article::tableName(), 'category_id');
        $this->addForeignKey('fk_article_category', \centigen\i18ncontent\models\Article::tableName(), 'category_id', \centigen\i18ncontent\models\ArticleCategory::tableName(), 'id');

        $this->createIndex('idx_parent_id', \centigen\i18ncontent\models\ArticleCategory::tableName(), 'parent_id');
        $this->addForeignKey('fk_article_category_section', \centigen\i18ncontent\models\ArticleCategory::tableName(), 'parent_id', \centigen\i18ncontent\models\ArticleCategory::tableName(), 'id', 'cascade', 'cascade');

        $this->createTable(\centigen\i18ncontent\models\ArticleCategoryTranslation::tableName(), [
            'id' => $this->primaryKey(11),
            'article_category_id' => $this->integer(11)->notNull(),
            'locale' => $this->string(11)->notNull(),
            'title' => $this->string(512)->notNull(),
            'body' => $this->text()
        ], $tableOptions);

        $this->createTable(\centigen\i18ncontent\models\ArticleTranslation::tableName(), [
            'id' => $this->primaryKey(11),
            'article_id' => $this->integer(11)->notNull(),
            'locale' => $this->string(15)->notNull(),
            'title' => $this->string(512)->notNull(),
            'body' => $this->text()->notNull()
        ]);

        $this->createIndex('IDX_article_category_translation_article_category_id', \centigen\i18ncontent\models\ArticleCategoryTranslation::tableName(), 'article_category_id');
        $this->addForeignKey('FK_article_category_translation_article_category_id', \centigen\i18ncontent\models\ArticleCategoryTranslation::tableName(), 'article_category_id', \centigen\i18ncontent\models\ArticleCategory::tableName(), 'id', 'cascade', 'cascade');
        $this->createIndex('IDX_article_translation_article_id', \centigen\i18ncontent\models\ArticleTranslation::tableName(), 'article_id');
        $this->addForeignKey('FK_article_translation_article_id', \centigen\i18ncontent\models\ArticleTranslation::tableName(), 'article_id', \centigen\i18ncontent\models\Article::tableName(), 'id', 'cascade', 'cascade');

        $this->insert(\centigen\i18ncontent\models\ArticleCategory::tableName(), [
            'slug' => 'news',
            'status' => 1,
            'created_at' => time(),
            'updated_at' => time()
        ]);

        $this->insert(\centigen\i18ncontent\models\ArticleCategoryTranslation::tableName(), [
            'article_category_id' => 1,
            'locale' => 'en-US',
            'title' => 'News'
        ]);

        $this->insert(\centigen\i18ncontent\models\ArticleCategoryTranslation::tableName(), [
            'article_category_id' => 1,
            'locale' => 'ru-RU',
            'title' => 'новости'
        ]);

    }

    public function safeDown()
    {
        $this->dropForeignKey('FK_article_category_translation_article_category_id', \centigen\i18ncontent\models\ArticleCategoryTranslation::tableName());
        $this->dropForeignKey('FK_article_translation_article_id', \centigen\i18ncontent\models\ArticleTranslation::tableName());
        $this->dropForeignKey('fk_article_author', \centigen\i18ncontent\models\Article::tableName());
        $this->dropForeignKey('fk_article_updater', \centigen\i18ncontent\models\Article::tableName());
        $this->dropForeignKey('fk_article_category', \centigen\i18ncontent\models\Article::tableName());
        $this->dropForeignKey('fk_article_category_section', \centigen\i18ncontent\models\ArticleCategory::tableName());

        $this->dropTable(\centigen\i18ncontent\models\ArticleTranslation::tableName());
        $this->dropTable(\centigen\i18ncontent\models\ArticleCategoryTranslation::tableName());
        $this->dropTable(\centigen\i18ncontent\models\Article::tableName());
        $this->dropTable(\centigen\i18ncontent\models\ArticleCategory::tableName());
    }

}
