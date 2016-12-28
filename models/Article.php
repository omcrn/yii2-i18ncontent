<?php

namespace centigen\i18ncontent\models;

use centigen\i18ncontent\models\query\ArticleQuery;
use trntv\filekit\behaviors\UploadBehavior;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;

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
 * @property integer $category_id
 * @property integer $status
 * @property integer $published_at
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property string $title
 * @property string $body
 * @property ArticleCategory $category
 * @property ArticleAttachment[] $articleAttachments
 * @property ArticleTranslations[] $translations
 * @property ArticleTranslations $activeTranslation
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

    /**
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     * @var ArticleTranslations[]
     */
    public $newTranslations = [];

    public static $translateModelForeignKey = 'article_id';

    public static $translateModel = ArticleTranslations::class;

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
        return (new ArticleQuery(get_called_class()))->with('activeTranslation');
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
                'baseUrlAttribute' => 'base_url',
                'typeAttribute' => 'type',
                'sizeAttribute' => 'size',
                'nameAttribute' => 'name',
            ],
            [
                'class' => UploadBehavior::className(),
                'attribute' => 'thumbnail',
                'pathAttribute' => 'thumbnail_path',
                'baseUrlAttribute' => 'thumbnail_base_url'
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id'], 'required'],
            [['slug'], 'unique'],
            [['published_at'], 'default', 'value' => time()],
            [['published_at'], 'filter', 'filter' => 'strtotime'],
            [['category_id'], 'exist', 'targetClass' => ArticleCategory::className(), 'targetAttribute' => 'id'],
            [['author_id', 'updater_id', 'status'], 'integer'],
            [['slug', 'thumbnail_base_url', 'thumbnail_path', 'url'], 'string', 'max' => 2024],
            [['view'], 'string', 'max' => 255],
            [['attachments', 'thumbnail'], 'safe']
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
            'thumbnail' => Yii::t('i18ncontent', 'Thumbnail'),
            'url' => Yii::t('i18ncontent', 'Url'),
            'author_id' => Yii::t('i18ncontent', 'Author'),
            'updater_id' => Yii::t('i18ncontent', 'Updater'),
            'category_id' => Yii::t('i18ncontent', 'Category'),
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
    public function getCategory()
    {
        return $this->hasOne(ArticleCategory::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticleAttachments()
    {
        return $this->hasMany(ArticleAttachment::className(), ['article_id' => 'id']);
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
            ->where([
                'category_id' => $cat->id,
                'status' => self::STATUS_PUBLISHED
            ]);
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
        return Article::find()
            ->from(self::tableName().' a')
            ->innerJoin('{{%article_category}} ac', 'ac.id = a.category_id')
            ->with('activeTranslation')
            ->where([
                'ac.slug' => $slug,
                'a.status' => self::STATUS_PUBLISHED,
                'ac.status' => self::STATUS_PUBLISHED
            ])->all();
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
            ->where([
                'slug' => $slug,
                'status' => self::STATUS_PUBLISHED
            ])->one();
    }

    public function getTitle()
    {
        return $this->activeTranslation ? $this->activeTranslation->title : '';
    }

    public function getBody()
    {
        return $this->activeTranslation ? $this->activeTranslation->getBody() : '';
    }

	public function getThumbnailPath()
    {
        return Yii::getAlias('@storageUrl') . '/source/'.ltrim($this->thumbnail_path, '/');
    }
}
