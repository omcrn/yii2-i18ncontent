<?php

namespace centigen\i18ncontent\models;

use centigen\i18ncontent\models\query\ArticleCategoryQuery;
use Yii;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Exception;
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
    public function load($data, $formName = null, $locales = [])
    {
        if (!parent::load($data, $formName)) {
            return false;
        }

//        \centigen\base\helpers\UtilHelper::vardump($data);exit;
        $translations = ArrayHelper::getValue($data, 'ArticleCategoryTranslations');
        $data = [];
        foreach ($locales as $loc => $locale) {
            if (Yii::$app->language == $loc) {
                $this->title = $translations['title'][$loc];
            }
            $data['translations'][] = [
                'locale' => $loc,
                'title' => $translations['title'][$loc],
                'body' => isset($translations['body']) ? $translations['body'][$loc] : null
            ];
        }

        $this->newTranslations = [];
        $allValid = true;
        foreach ($data['translations'] as $item) {
            $translation = new ArticleCategoryTranslations();
            $this->newTranslations[] = $translation;

            $translation->attributes = [
                'article_category_id' => $this->id,
                'locale' => $item['locale'],
                'title' => $item['title'],
                'body' => $item['body']
            ];
            if (!$translation->validate()) {
                $allValid = false;
            }
        }
        if (!$allValid) {
            return false;
        }

        return true;

    }

    /**
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     * @inheritdoc
     */
    public function save($runValidation = true, $attributeNames = null)
    {
        if (!$this->validate() || !parent::save($runValidation, $attributeNames)) {
            return false;
        }

        return true;
    }

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
     * Get Article category by slug
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

    public static function processAndUpdate(ArticleCategory $model, $translations, $modelData, $translationData, $locales)
    {
        $transaction = Yii::$app->db->beginTransaction();

        $data = [];
        foreach ($locales as $loc => $locale) {
            $data['translations'][$loc] = [
                'title' => $translationData['title'][$loc],
                'body' => isset($translationData['body']) ? $translationData['body'][$loc] : null
            ];
        }

        $model->attributes = $modelData;

        try {

            if (!$model->validate() || !$model->save()) {
//                \ChromePhp::error($model->errors);
                return false;
            }

            foreach ($data['translations'] as $loc => $item) {
                $currentTrans = null;
                foreach ($translations as $trans) {
                    if ($loc === $trans->locale) {
                        $currentTrans = $trans;
                        break;
                    }
                }

                if (!$currentTrans) {
                    $currentTrans = new ArticleCategoryTranslations();
                }

                $currentTrans->article_category_id = $model->id;
                $currentTrans->locale = $loc;
                $currentTrans->title = $item['title'];
                $currentTrans->body = $item['body'];

                if (!$currentTrans->validate() || !$currentTrans->save()) {
                    $transaction->rollBack();
                    return false;
                }
            }
        } catch (Exception $ex) {
//            \ChromePhp::error($ex);
            $transaction->rollBack();
            return false;
        }

        $transaction->commit();
        return true;
    }

}
