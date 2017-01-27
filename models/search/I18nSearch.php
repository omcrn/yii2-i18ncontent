<?php

namespace centigen\i18ncontent\models\search;

use centigen\i18ncontent\models\I18nMessage;
use centigen\i18ncontent\models\I18nSourceMessage;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * I18nSearch represents the model behind the search form about `centigen\i18ncontent\models\I18nMessage`.
 *
 * @property string $translations
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
        $sm = \centigen\i18ncontent\models\I18nSourceMessage::tableName();
        $query = I18nSourceMessage::find()
//
//            ->with('sourceMessageModel')
//            ->joinWith('sourceMessageModel')
        ;

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
//            'sort' => [
//                'attributes' => [
//                    'category',
//                    'message',
//                    'language',
//                    'translation'
//                ]
//            ]
        ]);
//        $dataProvider->sort->attributes[] = 'category';

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            "$sm.id" => $this->id
        ]);

        if ($this->translation){
            $m = I18nMessage::tableName();
            $query->leftJoin($m, "$m.id = $sm.id")
                ->andWhere(['like', "$m.translation", $this->translation]);
        }

        $query
            ->andFilterWhere(['like', "$sm.message", $this->message])
            ->andFilterWhere(['like', "$sm.category", $this->category]);


        return $dataProvider;
    }
}
