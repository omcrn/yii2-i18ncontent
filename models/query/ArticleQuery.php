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
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     * @param Connection $bd
     * @return Article|array|mixed|null|\yii\db\ActiveRecord
     */
    public function one($bd = null)
    {
        return $this->one();
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
