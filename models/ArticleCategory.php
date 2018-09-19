<?php

namespace centigen\i18ncontent\models;

use centigen\base\helpers\DateHelper;
use centigen\i18ncontent\models\query\ArticleCategoryQuery;
use Yii;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "article_category".
 *
 * @property integer $id
 * @property string $slug
 * @property string $view
 * @property integer $status
 * @property integer $published_at
 *
 * @property ArticleCategoryArticle[] $articleCategoryArticles
 * @property ArticleCategory $parent
 * @property ArticleCategoryTranslation $activeTranslation
 * @property ArticleCategoryTranslation[] $translations
 */
class ArticleCategory extends TranslatableModel
{
    const STATUS_ACTIVE = 1;
    const STATUS_DRAFT = 0;

    public $title = null;

    /**
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     * @var ArticleCategoryTranslation[]
     */
    public $newTranslations = [];

    public static $translateModelForeignKey = 'article_category_id';

    public static $translateModel = ArticleCategoryTranslation::class;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%article_category}}';
    }

    public function beforeValidate()
    {
        try{
            if ($this->published_at) {
                $this->published_at = DateHelper::fromFormatIntoMysql(Yii::$app->formatter->getPhpDatetimeFormat(), $this->published_at);
            }
        }catch(\InvalidArgumentException $e){

        }

        return parent::beforeValidate();
    }

    /**
     * @return ArticleCategoryQuery
     */
    public static function find()
    {
        return (new ArticleCategoryQuery(get_called_class()))->with('activeTranslation');
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title'
            ]
        ];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['slug'], 'unique'],
            [['slug', 'view'], 'string', 'max' => 1024],
            ['status', 'integer'],
            ['parent_id', 'exist', 'targetClass' => ArticleCategory::className(), 'targetAttribute' => 'id'],
            [['published_at'], 'default', 'value' => time()],
            [['published_at'], 'filter', 'filter' => 'strtotime', 'when' => function ($model) {
                return is_string($model->published_at);
            }],
            [['published_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('i18ncontent', 'ID'),
            'slug' => Yii::t('i18ncontent', 'Slug'),
            'view' => Yii::t('i18ncontent', 'View'),
            'parent_id' => Yii::t('i18ncontent', 'Parent Category'),
            'status' => Yii::t('i18ncontent', 'Status'),
            'published_at' => Yii::t('i18ncontent', 'Published At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticleCategoryArticles()
    {
        return $this->hasMany(ArticleCategoryArticle::className(), ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(ArticleCategory::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildCategories()
    {
        return $this->hasMany(ArticleCategory::className(), ['parent_id' => 'id']);
    }

    /**
     * Get ArticleCategory id by its slug
     *
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     * @param $slug
     * @return int|mixed
     */
    public static function getIdBySlug($slug)
    {
        $self = self::find()->where(['slug' => $slug])->one();
        return $self ? $self->id : -1;
    }

    /**
     * Get all ids of ArticleCategory which are direct children of given category plus id of given category
     *
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     * @param $slug
     * @return array
     */
    public static function getIdsBySlug($slug)
    {
        $ids = [];
        $id = self::getIdBySlug($slug);
        array_push($ids, $id);
        $self = self::find()->select('id')->where(['parent_id' => $id])->asArray()->all();

        if (!empty($self)) {
            foreach ($self as $val) {
                $ids[] = $val['id'];
            }
        }
        return $ids;
    }

    /**
     * Get all article categories as map where key is `ArticleCategory::id`
     * and value is ArticleCategory::title. If $withParentCategory
     * is true, array item value will also contain parent `ArticleCategory::title`
     *
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     * @param bool $withParentCategory
     * @return array
     */
    public static function getCategories($withParentCategory = false)
    {
        $query = self::find();

        if ($withParentCategory) {
            $query->with('parent');
        }

        return self::convertToMap($query->all(), $withParentCategory);
    }

    /**
     * Get article categories as map where key is `ArticleCategory::id`
     * and value is ArticleCategory::title. If $withParentCategory
     * is true, array item value will also contain parent `ArticleCategory::title`
     *
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     * @param bool $withParentCategory
     * @return array
     */
    public static function getActiveCategories($withParentCategory = false)
    {
        $query = self::find()
            ->active();
        if ($withParentCategory) {
            $query->with('parent');
        }

        return self::convertToMap($query->all(), $withParentCategory);
    }

    /**
     * Convert given ArticleCategory array into map, where key is `ArticleCategory::id`
     * and value is ArticleCategory::title. If $withParentCategory
     * is true, array item value will also contain parent `ArticleCategory::title`
     *
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     * @param ArticleCategory[] $categories
     * @param bool $withParentCategory
     * @return array
     */
    protected static function convertToMap($categories, $withParentCategory = false)
    {
        $return = [];
        foreach ($categories as $category) {
            if ($category->activeTranslation) {
                $text = $category->getTitle();
                if ($withParentCategory && $category->parent) {
                    $text = $category->parent->getTitle() . ' - ' . $text;
                }
                $return[$category->id] = $text;
            }
        }

        return $return;
    }

    /**
     * Get active ArticleCategory by slug
     *
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     * @param $slug
     * @return $this
     */
    public static function getBySlug($slug)
    {
        return self::find()
            ->where([
                'slug' => $slug,
                'status' => self::STATUS_ACTIVE
            ])->one();
    }

    /**
     * Find categories by parent category slug
     *
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     * @param $slug
     * @return ArticleCategory[]
     */
    public static function getChildCategoriesByParentSlug($slug)
    {
        return self::find()
            ->select('ac.*')
            ->from(self::tableName() . ' ac')
            ->innerJoin(self::tableName() . ' ac1', 'ac1.id = ac.parent_id')
            ->with('activeTranslation')
            ->where(['ac1.slug' => $slug])
            ->all();
    }

    public function getTitle()
    {
        return $this->activeTranslation ? $this->activeTranslation->title : '';
    }

}
