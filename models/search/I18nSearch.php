<?php

namespace centigen\i18ncontent\models\search;

use centigen\i18ncontent\models\I18nSourceMessage;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * I18nMessageSearch represents the model behind the search form about `backend\modules\i18n\models\I18nMessage`.
 */
class I18nSearch extends I18nSourceMessage
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['category', 'message', 'language', 'translation'], 'safe'],
        ];
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), ['language', 'translation']);
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
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $m = \centigen\i18ncontent\models\I18nMessage::tableName();
        $sm = \centigen\i18ncontent\models\I18nSourceMessage::tableName();
        $query = I18nSourceMessage::find()
            ->select([
                "$sm.*, $m.*"
            ])
            ->asArray()
            ->leftJoin($m, "$m.id = $sm.id")
//            ->with('sourceMessageModel')
//            ->joinWith('sourceMessageModel')
        ;

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'category',
                    'message',
                    'language',
                    'translation'
                ]
            ]
        ]);
//        $dataProvider->sort->attributes[] = 'category';

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            '{{%i18n_source_message}}.id' => $this->id
        ]);

        $query->andFilterWhere(['like', "$m.language", $this->language])
            ->andFilterWhere(['like', "$m.translation", $this->translation])
            ->andFilterWhere(['like', "$sm.message", $this->sourceMessage])
            ->andFilterWhere(['like', "$sm.category", $this->category]);


        return $dataProvider;
    }
}
