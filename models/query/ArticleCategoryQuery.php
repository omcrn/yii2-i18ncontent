<?php

namespace centigen\i18ncontent\models\query;

use centigen\i18ncontent\models\ArticleCategory;
use yii\db\ActiveQuery;

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
}
