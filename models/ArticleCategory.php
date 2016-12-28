<?php

namespace centigen\i18ncontent\models;

use centigen\i18ncontent\models\query\ArticleCategoryQuery;
use Yii;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "article_category".
 *
 * @property integer $id
 * @property string $slug
 * @property string $view
 * @property integer $status
 *
 * @property Article[] $articles
 * @property ArticleCategory $parent
 * @property ArticleCategoryTranslations $activeTranslation
 * @property ArticleCategoryTranslations[] $translations
 */
class ArticleCategory extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_DRAFT = 0;

    public $title = null;

    /**
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     * @var ArticleCategoryTranslations[]
     */
    public $newTranslations = [];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%article_category}}';
    }

    /**
     * @return ArticleCategoryQuery
     */
    public static function find()
    {
        return (new ArticleCategoryQuery(get_called_class()))->with('activeTranslation');
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
            ['parent_id', 'exist', 'targetClass' => ArticleCategory::className(), 'targetAttribute' => 'id']
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
            'status' => Yii::t('i18ncontent', 'Active')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticles()
    {
        return $this->hasMany(Article::className(), ['category_id' => 'id']);
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
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(ArticleCategoryTranslations::className(), ['article_category_id' => 'id']);
    }

    public function getActiveTranslation()
    {
        return $this->hasOne(ArticleCategoryTranslations::className(), ['article_category_id' => 'id'])->where([
            'locale' => Yii::$app->language
        ]);
    }

    /**
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     * @inheritdoc
     */
    public function load($postData, $formName = null)
    {
        if (!parent::load($postData, $formName)) {
            return false;
        }

        $className = \yii\helpers\StringHelper::basename(\centigen\i18ncontent\models\ArticleCategoryTranslations::className());
        $translations = ArrayHelper::getValue($postData, $className);
        $this->newTranslations = [];

        $allValid = true;
        foreach ($translations as $loc => $modelData) {
            $modelData['locale'] = $loc;
            if (Yii::$app->language == $loc) {
                $this->title = $modelData['title'];
            }
            $translation = $this->isNewRecord ?
                new ArticleCategoryTranslations() :
                $this->findTranslationByLocale($loc);

            $this->newTranslations[] = $translation;
            if (!$translation->load($modelData, '')) {
                $allValid = false;
            }
        }

        return $allValid;
    }

    /**
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     * @inheritdoc
     */
    public function save($runValidation = true, $attributeNames = null)
    {
        $transaction = Yii::$app->db->beginTransaction();
        if (!$this->validate() || !parent::save($runValidation, $attributeNames)) {
            return false;
        }

        $allSaved = true;
        foreach ($this->newTranslations as $translation) {
            $translation->article_category_id = $this->id;
            if (!$translation->save()) {
                $allSaved = false;
            }
        }

        if ($allSaved) {
            $transaction->commit();
        } else {
            $transaction->rollBack();
        }

        return $allSaved;
    }

    /**
     * Find ArticleCategoryTranslations object from `translations` array by locale
     *
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     * @param $locale
     * @return ArticleCategoryTranslations|null
     */
    private function findTranslationByLocale($locale)
    {
        foreach ($this->translations as $translation) {
            if ($translation->locale === $locale) {
                return $translation;
            }
        }

        return null;
    }

    /**
     * Get article categories as map where key is `ArticleCategory::id` and value `activeTranslation->title`
     *
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     * @return array
     */
    public static function getCategories()
    {
        $categories = self::find()
            ->active()
            ->all();

        $return = [];

        foreach ($categories as $category) {
            if ($category->activeTranslation) {
                $return[$category->id] = $category->activeTranslation->title;
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
