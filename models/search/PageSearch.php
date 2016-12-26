<?php

namespace centigen\i18ncontent\models\search;

use centigen\i18ncontent\models\Page;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PageSearch represents the model behind the search form about `centigen\i18ncontent\models\Page`.
 */
class PageSearch extends Page
{
    public $title = null;
    public $body     = null;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['slug', 'title', 'body'], 'safe'],
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
        $query = Page::find()
            ->from(self::tableName().' p')
            ->innerJoin('{{%page_translations}} t', 't.page_id = p.id and t.locale = :locale', ['locale' => Yii::$app->language]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['title'] = [
            'asc' => ['t.title' => SORT_ASC],
            'desc' => ['t.title' => SORT_DESC],
        ];

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $this->slug = $params['PageSearch']['slug'];

        $query->andFilterWhere([
            'p.id' => $this->id,
            'p.status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'p.slug', $this->slug])
            ->andFilterWhere(['like', 't.title', $this->title])
            ->andFilterWhere(['like', 't.body', $this->body]);

//        \ChromePhp::log(QueryHelper::getRawSql($query));

        return $dataProvider;
    }
}
