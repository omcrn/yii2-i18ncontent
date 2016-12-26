<?php

namespace centigen\i18ncontent\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\Exception;

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

    public function getActiveTranslation()
    {
        return $this->hasOne(PageTranslations::className(), ['page_id' => 'id'])->where([
            'locale' => Yii::$app->language
        ]);
    }

    public function getTitle()
    {
        return $this->activeTranslation ? $this->activeTranslation->title : '';
    }

    public function getBody()
    {
        return $this->activeTranslation ? $this->activeTranslation->body : '';
    }

    /**
     * Find Page by slug and status
     *
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     * @param $slug
     * @param $status
     * @return Page
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
     * @return Page[]
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
     * @return Page
     */
    public static function getById($id)
    {
        return self::find()
            ->where(['id' => $id])
            ->with('translations')->one();

    }

    public static function processAndSave($modelData, $translations, $locales)
    {
        $model = new self();
        $model->attributes = $modelData;

        $data = [];
        foreach ($locales as $loc => $locale) {
            if (Yii::$app->language == $loc) {
                $model->title = $translations['title'][$loc];
            }
            $data['translations'][] = [
                'locale' => $loc,
                'title' => $translations['title'][$loc],
                'body' => $translations['body'][$loc],
                'meta_title' => $translations['meta_title'][$loc],
                'meta_keywords' => $translations['meta_keywords'][$loc],
                'meta_description' => $translations['meta_description'][$loc],
            ];
        }

        $transaction = Yii::$app->db->beginTransaction();
        if (!$model->validate() || !$model->save()) {
            return false;
        }

        foreach ($data['translations'] as $item) {
            $text = new PageTranslations();
            $text->attributes = [
                'page_id' => $model->id,
                'locale' => $item['locale'],
                'title' => $item['title'],
                'body' => $item['body'],
                'meta_title' => $item['meta_title'],
                'meta_keywords' => $item['meta_keywords'],
                'meta_description' => $item['meta_description']
            ];

            if (!$text->validate() || !$text->save()) {
                $transaction->rollBack();
                return false;
            }
        }

        $transaction->commit();
        return true;
    }

    public static function processAndUpdate(Page $model, $translations, $modelData, $translationData, $locales)
    {
        $transaction = Yii::$app->db->beginTransaction();

        $data = [];
        foreach ($locales as $loc => $locale) {
            $data['translations'][$loc] = [
                'title' => $translationData['title'][$loc],
                'body' => $translationData['body'][$loc],
                'meta_title' => $translationData['meta_title'][$loc],
                'meta_keywords' => $translationData['meta_keywords'][$loc],
                'meta_description' => $translationData['meta_description'][$loc]
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
                    $currentTrans = new PageTranslations();
                }

                $currentTrans->page_id = $model->id;
                $currentTrans->locale = $loc;
                $currentTrans->title = $item['title'];
                $currentTrans->body = $item['body'];
                $currentTrans->meta_title = $item['meta_title'];
                $currentTrans->meta_keywords = $item['meta_keywords'];
                $currentTrans->meta_description = $item['meta_description'];

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
