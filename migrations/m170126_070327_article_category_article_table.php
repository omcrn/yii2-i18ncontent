<?php

use yii\db\Migration;
use yii\db\Schema;

class m170126_070327_article_category_article_table extends Migration
{
    public function up()
    {
        $this->createTable(\centigen\i18ncontent\models\ArticleCategoryArticle::tableName(), [
            'id' => Schema::TYPE_PK,
            'article_id' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'category_id' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'created_at' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
        ]);

//        $articles = \centigen\i18ncontent\models\Article::find()->all();
//        $trans = Yii::$app->db->beginTransaction();
//        foreach ($articles as $article) {
//            $articleArticleCategory =
//        }

        $this->createIndex('idx_article_category_article_article_id', \centigen\i18ncontent\models\ArticleCategoryArticle::tableName(), 'article_id');
        $this->addForeignKey('fk_article_category_article_article', \centigen\i18ncontent\models\ArticleCategoryArticle::tableName(), 'article_id',
            \centigen\i18ncontent\models\Article::tableName(), 'id', 'cascade', 'cascade');
        $this->createIndex('idx_article_category_article_article_category_id', \centigen\i18ncontent\models\ArticleCategoryArticle::tableName(), 'article_category_id');
        $this->addForeignKey('fk_article_category_article_article_category', \centigen\i18ncontent\models\ArticleCategoryArticle::tableName(), 'article_category_id',
            \centigen\i18ncontent\models\ArticleCategory::tableName(), 'id', 'cascade', 'cascade');


    }

    public function down()
    {
        $this->dropForeignKey('fk_article_category_article_article', \centigen\i18ncontent\models\ArticleCategoryArticle::tableName());
        $this->dropIndex('idx_article_category_article_article_id', \centigen\i18ncontent\models\ArticleCategoryArticle::tableName());

        $this->dropForeignKey('fk_article_category_article_article_category', \centigen\i18ncontent\models\ArticleCategoryArticle::tableName());
        $this->dropIndex('idx_article_category_article_category_id', \centigen\i18ncontent\models\ArticleCategoryArticle::tableName());

        $this->dropTable(\centigen\i18ncontent\models\ArticleCategoryArticle::tableName());
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
