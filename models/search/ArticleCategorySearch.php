<?php

namespace centigen\i18ncontent\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use centigen\i18ncontent\models\ArticleCategory;

/**
 * ArticleCategorySearch represents the model behind the search form about `centigen\i18ncontent\models\ArticleCategory`.
 *
 * @property string $parentCategory
 */
class ArticleCategorySearch extends ArticleCategory
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['slug', 'title', 'parentCategory'], 'safe'],
        ];
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), ['parentCategory']);
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
        $query = ArticleCategory::find()
            ->select('ac.*')
            ->from('{{%article_category}} ac')
            ->leftJoin(\centigen\i18ncontent\models\ArticleCategoryTranslation::tableName()
                . ' t', 't.article_category_id = ac.id and t.locale = :locale', ['locale' => Yii::$app->language])
            ->with(['parent', 'parent.activeTranslation'])

//        ->where([
//            't.locale' => Yii::$app->language
//        ])
        ;

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20
            ]
        ]);

        $dataProvider->sort->attributes['title'] = [
            'asc' => ['t.title' => SORT_ASC],
            'desc' => ['t.title' => SORT_DESC]
        ];
        $dataProvider->sort->attributes['parentCategory'] = [
            'asc' => ['t.title' => SORT_ASC],
            'desc' => ['t.title' => SORT_DESC]
        ];

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $this->slug = $params['ArticleCategorySearch']['slug'];

        $query->andFilterWhere([
            'ac.id' => $this->id,
            'ac.status' => $this->status,
        ]);

        if ($this->parentCategory){
            $query->leftJoin(ArticleCategory::tableName().' p', 'p.id = '.ArticleCategory::tableName().'.parent_id')
                ->andFilterWhere(['like', 'p.title', $this->parentCategory]);

        }

        $query
            ->andFilterWhere(['like', 'ac.slug', $this->slug])
            ->andFilterWhere(['like', 't.title', $this->title]);

        return $dataProvider;
    }
}
