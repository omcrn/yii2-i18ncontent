<?php

namespace centigen\i18ncontent\models;

use centigen\i18ncontent\models\query\ArticleQuery;
use trntv\filekit\behaviors\UploadBehavior;
use vendor\centigen\i18ncontent\helpers\Html;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Exception;
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
 * @property ArticleTranslations[] $articleTranslations
 * @property ArticleTranslations $activeTranslation
 */
class Article extends \yii\db\ActiveRecord
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
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(ArticleTranslations::className(), ['article_id' => 'id']);
    }

    public function getActiveTranslation()
    {
        return $this->hasOne(ArticleTranslations::className(), ['article_id' => 'id'])->where([
            'locale' => Yii::$app->language
        ]);
    }

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
     * Get article by slug
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

    public function getBody($decode = false)
    {
        return $this->activeTranslation ? $this->activeTranslation->getBody($decode) : '';
    }

    public static function processAndSave($modelData, $translations, $locales)
    {
        $model = new self();

        $data = [];
        foreach ($locales as $loc => $locale) {
            if (Yii::$app->language == $loc) {
                $model->title = $translations['title'][$loc];
            }
            $body = ArrayHelper::getValue($translations, 'body.'.$loc);
            $body = Html::encodeMediaItemUrls($body);
            $data['translations'][] = [
                'locale' => $loc,
                'title' => $translations['title'][$loc],
                'meta_title' => $translations['meta_title'][$loc],
                'meta_description' => $translations['meta_description'][$loc],
                'meta_keywords' => $translations['meta_keywords'][$loc],
                'body' => $body
            ];
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!$model->load(['Article' => $modelData]) || !$model->save()) {
//                \ChromePhp::error($model->errors);
                return false;
            }

            foreach ($data['translations'] as $item) {
                $text = new ArticleTranslations();
                $text->article_id = $model->id;
                $text->locale = $item['locale'];
                $text->title = $item['title'];
                $text->meta_title = $item['meta_title'];
                $text->meta_description = $item['meta_description'];
                $text->meta_keywords = $item['meta_keywords'];

                $text->body = $item['body'];

                if (!$text->validate() || !$text->save()) {
                    $transaction->rollBack();
//                    \ChromePhp::error($text->errors);
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

    public static function processAndUpdate(Article $model, $translations, $modelData, $translationData, $locales)
    {
        $transaction = Yii::$app->db->beginTransaction();

        $data = [];
        foreach ($locales as $loc => $locale) {
            $data['translations'][$loc] = [
                'title' => $translationData['title'][$loc],
                'body' => $translationData['body'][$loc],
                'meta_title' => $translationData['meta_title'][$loc],
                'meta_description' => $translationData['meta_description'][$loc],
                'meta_keywords' => $translationData['meta_keywords'][$loc],
            ];
        }

        try {
            $model->attributes = $modelData;
            if (!$model->save()) {
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
                    $currentTrans = new ArticleTranslations();
                }

                $currentTrans->article_id = $model->id;
                $currentTrans->locale = $loc;
                $currentTrans->title = $item['title'];
                $currentTrans->meta_title = $item['meta_title'];
                $currentTrans->meta_description = $item['meta_description'];
                $currentTrans->meta_keywords = $item['meta_keywords'];
//                $currentTrans->body = $item['body'];
                $currentTrans->body = Html::encodeMediaItemUrls($item['body']);

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

	public function getThumbnailPath()
    {
        return getenv('STORAGE_URL').'/source/'.$this->thumbnail_path;
    }
}
