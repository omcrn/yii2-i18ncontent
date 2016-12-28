<?php

namespace centigen\i18ncontent\models;

use centigen\i18ncontent\helpers\Html;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

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
 * @property PageTranslations[] $translations
 * @property PageTranslations $activeTranslation
 */
class Page extends \yii\db\ActiveRecord
{
    const STATUS_DRAFT = 0;
    const STATUS_PUBLISHED = 1;

    public $title = null;

    /**
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     * @var PageTranslations[]
     */
    public $newTranslations = [];

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
            'status' => Yii::t('i18ncontent', 'Active'),
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
    public function getTranslations()
    {
        return $this->hasMany(PageTranslations::className(), ['page_id' => 'id']);
    }

    /**
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     * @return ActiveQuery
     */
    public function getActiveTranslation()
    {
        return $this->hasOne(PageTranslations::className(), ['page_id' => 'id'])->where([
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

        $className = \yii\helpers\StringHelper::basename(\centigen\i18ncontent\models\PageTranslations::className());
        $translations = ArrayHelper::getValue($postData, $className);
        $this->newTranslations = [];

        $allValid = true;
        foreach ($translations as $loc => $modelData) {
            $modelData['locale'] = $loc;
            $modelData['body'] = Html::encodeMediaItemUrls($modelData['body']);

            if (Yii::$app->language == $loc) {
                $this->title = $modelData['title'];
            }
            $translation = $this->isNewRecord ?
                new PageTranslations() :
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
            $translation->page_id = $this->id;
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
     * Find PageTranslation object from `translations` array by locale
     *
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     * @param $locale
     * @return PageTranslations|null
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
        return $this->activeTranslation ? $this->activeTranslation->title : '';
    }

    public function getBody()
    {
        return $this->activeTranslation ? $this->activeTranslation->getBody() : '';
    }
}
