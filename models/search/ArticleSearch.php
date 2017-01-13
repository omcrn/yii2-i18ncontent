<?php

namespace centigen\i18ncontent\models\search;

use centigen\i18ncontent\models\ArticleTranslation;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use centigen\i18ncontent\models\Article;

/**
 * ArticleSearch represents the model behind the search form about `centigen\i18ncontent\models\Article`.
 */
class ArticleSearch extends Article
{

    public $author = null;

    /**
     * @var array
     */
    public $catIds;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'category_id', 'status', 'published_at', 'created_at', 'position'], 'integer'],
            [['author', 'slug', 'title', 'catIds'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     * @return ActiveDataProvider
     */
    public function search($params)
    {
//        \ChromePhp::log($params);
        $query = Article::find();
        $query->from('{{%article}} a');
        $query->innerJoin(ArticleTranslation::tableName().'at', 'at.article_id = a.id AND at.locale = :locale', ['locale' => Yii::$app->language]);
        $query->innerJoin('{{%user}} u', 'u.id = a.author_id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['title'] = [
            'asc' => ['at.title' => SORT_ASC],
            'desc' => ['at.title' => SORT_DESC]
        ];
        $dataProvider->sort->attributes['author'] = [
            'asc' => ['u.username' => SORT_ASC],
            'desc' => ['u.username' => SORT_DESC]
        ];
       $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        //$this->slug = $params['ArticleSearch']['slug'];

        $query->andFilterWhere([
            'a.id' => $this->id,
            'a.category_id' => $this->category_id,
            'a.status' => $this->status,
            'a.position' => $this->position,
            'a.published_at' => $this->published_at,
            'a.created_at' => $this->created_at
        ]);

        $query->andFilterWhere(['like', 'a.slug', $this->slug])
            ->andFilterWhere(['like', 'at.title', $this->title]);

        if ($this->author) {
            $query->andFilterWhere([
                'like', 'u.username', $this->author
            ]);
        }

//        \ChromePhp::log(QueryHelper::getRawSql($query));

        return $dataProvider;
    }


    /**
     * Creates data provider instance with search query applied
     * @return ActiveDataProvider
     */
    public function searchByCategory($params)
    {
        $query = Article::find();
        $query->from('{{%article}} a');
//        $query->innerJoin('article_category', 'article_category.id = a.category_id');
        $query->innerJoin(ArticleTranslation::tableName().' at', 'at.article_id = a.id AND at.locale = :locale', ['locale' => Yii::$app->language]);
        $query->innerJoin('{{%user}} u', 'u.id = a.author_id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['title'] = [
            'asc' => ['at.title' => SORT_ASC],
            'desc' => ['at.title' => SORT_DESC]
        ];
        $dataProvider->sort->attributes['author'] = [
            'asc' => ['u.username' => SORT_ASC],
            'desc' => ['u.username' => SORT_DESC]
        ];
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['a.category_id' => $this->catIds]);
        $query->andFilterWhere([
            'a.id' => $this->id,
            'a.status' => $this->status,
            'a.published_at' => $this->published_at,
            'a.created_at' => $this->created_at,
            'a.category_id' => $this->category_id,
        ]);

        $query->andFilterWhere(['like', 'a.slug', $this->slug])
            ->andFilterWhere(['like', 'at.title', $this->title]);

        if ($this->author) {
            $query->andFilterWhere([
                'like', 'u.username', $this->author
            ]);
        }

//        print_r(QueryHelper::getRawSql($query));exit;

        return $dataProvider;
    }
}
