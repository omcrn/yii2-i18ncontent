<?php

namespace centigen\i18ncontent\models\query;

use centigen\i18ncontent\models\Article;
use centigen\i18ncontent\models\ArticleCategory;
use centigen\i18ncontent\models\ArticleCategoryArticle;
use yii\db\ActiveQuery;
use yii\db\Connection;

class ArticleQuery extends ActiveQuery
{
    private $joinedOnCategory = false;
    private $joinedOnArticleCategoryArticle = false;

    /**
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     * @param null $bd
     * @return array|Article|mixed|null|\yii\db\ActiveRecord
     * @internal param Connection $db
     */
    public function one($bd = null)
    {
        return parent::one($bd);
    }

    /**
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     * @param null $db
     * @return Article[]|array|\yii\db\ActiveRecord[]
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @param $slug
     * @return $this
     */
    public function bySlug($slug)
    {
        return $this->andWhere(['{{%article}}.slug' => $slug]);
    }

    public function published()
    {
        $this->andWhere(['{{%article}}.status' => Article::STATUS_PUBLISHED]);
        $this->andWhere(['<', '{{%article}}.published_at', time()]);
        return $this;
    }

    /**
     * @param $categoryId
     * @return $this
     */
    public function byCategoryId($categoryId){
        $this->joinOnArticleCategoryArticle();
        return $this->andWhere([ArticleCategoryArticle::tableName().'.category_id' => $categoryId]);
    }

    /**
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     * @param $categorySlug
     * @return self
     */
    public function byCategorySlug($categorySlug)
    {
        if (!$this->joinedOnCategory){
            $this->joinOnCategory();
        }
        return $this->andWhere([ArticleCategory::tableName().'.slug' => $categorySlug]);
    }

    /**
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     * @return self
     */
    public function joinOnArticleCategoryArticle()
    {
        if (!$this->joinedOnArticleCategoryArticle){
            $this->joinedOnArticleCategoryArticle = true;
            $this->innerJoin(ArticleCategoryArticle::tableName(),
                ArticleCategoryArticle::tableName().'.article_id = '.Article::tableName().'.id');
        }
        return $this;
    }

    /**
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     * @return self
     */
    public function joinOnCategory()
    {
        $this->joinOnArticleCategoryArticle();
        if (!$this->joinedOnCategory){
            $this->joinedOnCategory = true;
            $this->innerJoin(ArticleCategory::tableName(), ArticleCategory::tableName().'.id = '.ArticleCategoryArticle::tableName().'.category_id');
        }
        return $this;
    }

    /**
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     * @return self
     */
    public function orderByPosition()
    {
        return $this->orderBy(['{{%article}}.position' => SORT_ASC]);
    }

    /**
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     * @return self
     */
    public function categoryActive()
    {
        $this->joinOnCategory();
        return $this->andWhere([ArticleCategory::tableName().'.status' => ArticleCategory::STATUS_ACTIVE]);
    }
}
