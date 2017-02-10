<?php

namespace centigen\i18ncontent\models;

use centigen\base\helpers\DateHelper;
use centigen\i18ncontent\models\query\ArticleQuery;
use trntv\filekit\behaviors\UploadBehavior;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "article".
 *
 * @property integer $id
 * @property string $slug
 * @property string $view
 * @property string $thumbnail_base_url
 * @property string $thumbnail_path
 * @property string $url
 * @property array $attachments
 * @property integer $author_id
 * @property integer $updater_id
 * @property integer $status
 * @property integer $published_at
 * @property integer $position
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property string $title
 * @property string $body
 * @property ArticleCategoryArticle[] $articleCategoryArticles
 * @property ArticleAttachment[] $articleAttachments
 * @property ArticleTranslation[] $translations
 * @property ArticleTranslation $activeTranslation
 */
class Article extends TranslatableModel
{
    const STATUS_PUBLISHED = 1;
    const STATUS_DRAFT = 0;

    /**
     * @var array
     */
    public $attachments;

    /**
     * @var array
     */
    public $thumbnail;

    public $title = null;


    public $articleCount = null;

    public $category_ids = [];

    /**
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     * @var ArticleTranslation[]
     */
    public $newTranslations = [];

    public static $translateModelForeignKey = 'article_id';

    public static $translateModel = ArticleTranslation::class;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%article}}';
    }

    /**
     * @return ArticleQuery
     */
    public static function find()
    {
        return (new ArticleQuery(get_called_class()))
            ->with(['translations', 'activeTranslation', 'articleAttachments', 'articleCategoryArticles']);
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'author_id',
                'updatedByAttribute' => 'updater_id',

            ],
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
                'immutable' => true
            ],
            [
                'class' => UploadBehavior::className(),
                'attribute' => 'attachments',
                'multiple' => true,
                'uploadRelation' => 'articleAttachments',
                'pathAttribute' => 'path',
                'baseUrlAttribute' => null,
                'typeAttribute' => 'type',
                'sizeAttribute' => 'size',
                'nameAttribute' => 'name',
            ],
            [
                'class' => UploadBehavior::className(),
                'attribute' => 'thumbnail',
                'pathAttribute' => 'thumbnail_path',
                'baseUrlAttribute' => null
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
            [['published_at'], 'default', 'value' => time()],
            [['published_at'], 'filter', 'filter' => 'strtotime', 'when' => function ($model) {
                return is_string($model->published_at);
            }],
            [['author_id', 'updater_id', 'position', 'status'], 'integer'],
            [['slug', 'thumbnail_base_url', 'thumbnail_path', 'url'], 'string', 'max' => 2024],
            [['view'], 'string', 'max' => 255],
            [['attachments', 'thumbnail', 'published_at'], 'safe'],
            ['category_ids', 'each', 'rule' => ['integer']],
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
            'view' => Yii::t('i18ncontent', 'Article View'),
            'category_ids' => Yii::t('i18ncontent', 'Article Categories'),
            'thumbnail' => Yii::t('i18ncontent', 'Thumbnail'),
            'position' => Yii::t('i18ncontent', 'Position'),
            'url' => Yii::t('i18ncontent', 'Url'),
            'author_id' => Yii::t('i18ncontent', 'Author'),
            'updater_id' => Yii::t('i18ncontent', 'Updater'),
            'status' => Yii::t('i18ncontent', 'Published'),
            'published_at' => Yii::t('i18ncontent', 'Published At'),
            'created_at' => Yii::t('i18ncontent', 'Created At'),
            'updated_at' => Yii::t('i18ncontent', 'Updated At')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(Yii::$app->getModule('i18ncontent')->userClass, ['id' => 'author_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdater()
    {
        return $this->hasOne(Yii::$app->getModule('i18ncontent')->userClass, ['id' => 'updater_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticleCategoryArticles()
    {
        return $this->hasMany(ArticleCategoryArticle::className(), ['article_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticleAttachments()
    {
        return $this->hasMany(ArticleAttachment::className(), ['article_id' => 'id']);
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

    public function save($runValidation = true, $attributeNames = null)
    {
        $transaction = Yii::$app->db->beginTransaction();
        if (parent::save()){

            if (!is_array($this->category_ids)){
                $this->category_ids = [];
            }
            $existingCategoryIds = ArrayHelper::getColumn($this->articleCategoryArticles, 'category_id');
            $toDeleteCategoryIds = array_diff($existingCategoryIds, $this->category_ids);
            $toAddCategoryIds = array_diff($this->category_ids, $existingCategoryIds);
//            \centigen\base\helpers\UtilHelper::vardump($toDeleteCategoryIds, $toAddCategoryIds);
//            exit;
            if ($this->removeCategories($toDeleteCategoryIds) && $this->addCategories($toAddCategoryIds)){
                $transaction->commit();
                return true;
            }
        }
        $transaction->rollBack();
        return false;
    }

    protected function removeCategories($categoryIds)
    {
        if (empty($categoryIds)){
            return true;
        }
        ArticleCategoryArticle::deleteAll(['article_id' => $this->id, 'category_id' => $categoryIds]);
        return true;
    }

    protected function addCategories($categoryIds)
    {
        if (empty($categoryIds)){
            return true;
        }
        $data = [];
        foreach ($categoryIds as $category_id){
            $data[] = [
                'article_id' => $this->id,
                'category_id' => $category_id,
                'updated_at' => time(),
                'created_at' => time()
            ];
        }

        Yii::$app->db->createCommand()->batchInsert(ArticleCategoryArticle::tableName(),
            ['article_id', 'category_id', 'updated_at', 'created_at'], $data)->execute();

        return true;
    }

    /**
     * Find Article-s by category. Return array of Article or ActiveQuery
     *
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     * @param ArticleCategory $cat
     * @param bool $getQuery
     * @return Article[]|\yii\db\ActiveRecord[]|ActiveQuery
     */
    public static function getArticlesByCategory(ArticleCategory $cat, $getQuery = true)
    {
        $query = Article::find()
            ->with('activeTranslation')
            ->byCategoryId($cat->id)
            ->published();
        return $getQuery ? $query : $query->all();
    }

    /**
     * Get Articles by category slug
     *
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     * @param $slug
     * @return Article[]
     */
    public static function getByCategorySlug($slug)
    {
        return Article::find()->byCategorySlug($slug)
            ->categoryActive()
            ->published()
            ->with('activeTranslation')
            ->all();
    }

    /**
     * Get single article by slug
     *
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     * @param $slug
     * @return Article
     */
    public static function getBySlug($slug)
    {
        return Article::find()
            ->with('activeTranslation')
            ->bySlug($slug)
            ->published()
            ->one();
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->activeTranslation ? $this->activeTranslation->title : '';
    }

    /**
     * @return mixed|string
     */
    public function getBody()
    {
        return $this->activeTranslation ? $this->activeTranslation->getBody() : '';
    }

    /**
     * @return mixed|string
     */
    public function getShortDescription()
    {
        return $this->activeTranslation ? $this->activeTranslation->getShortDescription() : '';
    }

    public function getThumbnailUrl()
    {
        if ($this->thumbnail_path) {
            return Yii::getAlias('@storageUrl') . '/source/' . ltrim($this->thumbnail_path, '/');
        }
        return null;
    }

    /**
     * Get slugs of this article categories
     *
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     * @return array
     */
    public function getArticleCategorySlugs()
    {
        $slugs = [];
        foreach ($this->articleCategoryArticles as $articleCategoryArticle) {
            $slugs[] = $articleCategoryArticle->articleCategory->slug;
        }

        return $slugs;
    }
}
