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
     * @param $slug
     * @return $this
     */
    public function bySlug($slug)
    {
        return $this->andWhere(['{{%article_category}}.slug' => $slug]);
    }

    public function byParentId($id)
    {
        return $this->andWhere(['parent_id'=>$id]);
    }
}
