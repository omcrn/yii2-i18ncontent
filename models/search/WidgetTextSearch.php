<?php

namespace centigen\i18ncontent\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use centigen\i18ncontent\models\WidgetText;

class WidgetTextSearch extends WidgetText
{

    public $title = null;
    public $body = null;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['key', 'title', 'body'], 'safe'],
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
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = WidgetText::find()
            ->from('{{%widget_text}} wt')
            ->innerJoin('{{%widget_text_translations}} t', 't.widget_text_id = wt.id and t.locale = :locale', ['locale' => Yii::$app->language]);


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['updated_at' => SORT_DESC]]
        ]);

        $dataProvider->sort->attributes['title'] = [
            'asc' => ['t.title' => SORT_ASC],
            'desc' => ['t.title' => SORT_DESC],
        ];

        if (!( $this->load($params) && $this->validate() )) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'wt.id' => $this->id,
            'wt.status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'wt.key', $this->key])
            ->andFilterWhere(['like', 't.title', $this->title])
            ->andFilterWhere(['like', 't.body', $this->body]);

        return $dataProvider;
    }
}
