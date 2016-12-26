<?php
/**
 * User: zura
 * Date: 10/27/2016
 * Time: 10:33 AM
 */

namespace centigen\i18ncontent\models\search;
use centigen\i18ncontent\models\WidgetMenu;
use yii\base\Model;
use yii\data\ActiveDataProvider;


/**
 * Class WidgetMenuSearch
 *
 * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
 * @package centigen\i18ncontent\models\search
 */
class WidgetMenuSearch extends WidgetMenu
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['key', 'title', 'items'], 'safe'],
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
        $query = WidgetMenu::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
        ]);
        $query->andFilterWhere(['like', 'key', $this->key])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'items', $this->items]);
        return $dataProvider;
    }
}