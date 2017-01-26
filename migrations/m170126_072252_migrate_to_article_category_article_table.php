<?php

use yii\db\Migration;

class m170126_072252_migrate_to_article_category_article_table extends Migration
{
    public function safeUp()
    {
        $articles = \centigen\i18ncontent\models\Article::find()->all();

        $trans = Yii::$app->db->beginTransaction();
        foreach ($articles as $article){
            $articleCategoryArticle = new \centigen\i18ncontent\models\ArticleCategoryArticle();
            $articleCategoryArticle->article_category_id = $article->category_id;
            $articleCategoryArticle->article_id = $article->id;
            if (!$articleCategoryArticle->save()){
                $trans->rollBack();
                return false;
            }
        }

        $trans->commit();

        $this->dropForeignKey('fk_article_category', \centigen\i18ncontent\models\Article::tableName());
        $this->dropIndex('idx_category_id', \centigen\i18ncontent\models\Article::tableName());
        $this->dropColumn(\centigen\i18ncontent\models\Article::tableName(), 'category_id');

        return null;
    }

    public function down()
    {
        echo "m170126_072252_migrate_to_article_category_article_table cannot be reverted.\n";

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
