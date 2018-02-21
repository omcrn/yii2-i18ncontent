<?php

namespace centigen\i18ncontent\models\query;

use centigen\i18ncontent\models\ArticleCategory;
use yii\db\ActiveQuery;
use yii\db\Connection;

class ArticleCategoryQuery extends ActiveQuery
{
    /**
     * @return $this
     */
    public function active()
    {
        $this->andWhere(['{{%article_category}}.status' => ArticleCategory::STATUS_ACTIVE]);

        return $this;
    }

    /**
     * @return $this
     */
    public function noParents()
    {
        $this->andWhere('{{%article_category}}.parent_id IS NULL');

        return $this;
    }


    /**
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     * @param Connection $db
     * @return ArticleCategory|array|mixed|null|\yii\db\ActiveRecord
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     * @param null $db
     * @return ArticleCategory[]|array|\yii\db\ActiveRecord[]
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * Search article category by slug
     *
     * @param $slug
     * @return self
     */
    public function bySlug($slug)
    {
        return $this->andWhere(['{{%article_category}}.slug' => $slug]);
    }

    /**
     * Search article categories by parent category id
     *
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     * @param $id
     * @return self
     */
    public function byParentId($id)
    {
        return $this->andWhere(['{{%article_category}}.parent_id'=>$id]);
    }

    /**
     * Search article categories by parent category slug
     *
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     * @param $slug
     * @return self
     */
    public function byParentSlug($slug)
    {
        return $this
            ->leftJoin(ArticleCategory::tableName().' p', 'p.id = '.ArticleCategory::tableName().'.parent_id')
            ->andWhere(['p.slug' => $slug])
            ;
    }
}
