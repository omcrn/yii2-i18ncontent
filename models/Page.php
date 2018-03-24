<?php

namespace centigen\i18ncontent\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "page".
 *
 * @property integer $id
 * @property string $slug
 * @property string $view
 * @property integer $status
 * @property integer $author_id
 * @property integer $updater_id
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property string $title
 * @property string $body
 * @property PageTranslation[] $translations
 * @property PageTranslation $activeTranslation
 * @property PageTranslation $defaultTranslation
 */
class Page extends TranslatableModel
{
    const STATUS_DRAFT = 0;
    const STATUS_PUBLISHED = 1;

    public $title = null;

    /**
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     * @var PageTranslation[]
     */
    public $newTranslations = [];

    public static $translateModelForeignKey = 'page_id';

    public static $translateModel = PageTranslation::class;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%page}}';
    }

    /**
     * @inheritdoc
     * @return ActiveQuery the newly created [[ActiveQuery]] instance.
     */
    public static function find()
    {
        return parent::find()->with('activeTranslation');
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
            'slug' => [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
                'ensureUnique' => true,
                'immutable' => true
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'author_id', 'updater_id'], 'integer'],
            [['slug'], 'unique'],
            [['slug'], 'string', 'max' => 2048],
            [['view'], 'string', 'max' => 255],
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
            'view' => Yii::t('i18ncontent', 'Page View'),
            'status' => Yii::t('i18ncontent', 'Status'),
            'author_id' => Yii::t('i18ncontent', 'Author'),
            'updater_id' => Yii::t('i18ncontent', 'Updater'),
            'created_at' => Yii::t('i18ncontent', 'Created At'),
            'updated_at' => Yii::t('i18ncontent', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(Yii::$app->i18ncontent->userClass, ['id' => 'author_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdater()
    {
        return $this->hasOne(Yii::$app->i18ncontent->userClass, ['id' => 'updater_id']);
    }

    /**
     * Find singel page by slug
     *
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     * @param $slug
     * @param $status
     * @return array|null|\yii\db\ActiveRecord|Page
     */
    public static function findBySlug($slug, $status)
    {
        return self::find()->where([
            'slug' => $slug,
            'status' => $status
        ])->one();
    }

    /**
     * Find Pages by author id
     *
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     * @param $userId
     * @return array|\yii\db\ActiveRecord[]|Page[]
     */
    public static function getActiveByAuthorId($userId)
    {
        return self::find()->where([
            'author_id' => $userId,
            'status' => self::STATUS_PUBLISHED
        ])->with('activeTranslation')->all();
    }

    /**
     * Find page by its id
     *
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     * @param $id
     * @return array|null|\yii\db\ActiveRecord|Page
     */
    public static function getById($id)
    {
        return self::find()
            ->where(['id' => $id])
            ->with('translations')->one();

    }

    public function getTitle()
    {
        return $this->getTranslation() ? $this->getTranslation()->title : '';
    }

    public function getShortDescription()
    {
        return $this->getTranslation() ? $this->getTranslation()->getShortDescription() : '';
    }

    public function getBody()
    {
        return $this->getTranslation() ? $this->getTranslation()->getBody() : '';
    }

    public function getTranslation()
    {
        return $this->activeTranslation ?: $this->defaultTranslation;
    }

    public function getDefaultTranslation()
    {
        return $this->hasOne(PageTranslation::className(), ['page_id' => 'id'])->where([
            'locale' => Yii::$app->sourceLanguage
        ]);
    }
}
