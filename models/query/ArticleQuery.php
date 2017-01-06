<?php

namespace centigen\i18ncontent\models\query;

use centigen\i18ncontent\models\Article;
use yii\db\ActiveQuery;
use yii\db\Connection;

class ArticleQuery extends ActiveQuery
{
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
        return $this->andWhere(['{{%article}}.category_id' => $categoryId]);
    }

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
}
