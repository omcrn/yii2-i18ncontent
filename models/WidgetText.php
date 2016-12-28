<?php

namespace centigen\i18ncontent\models;

use centigen\base\behaviors\CacheInvalidateBehavior;
use centigen\i18ncontent\helpers\Html;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "text_block".
 *
 * @property integer               $id
 * @property string                $key
 * @property integer               $status
 * @property integer               $created_at
 * @property integer               $updated_at
 *
 * @property WidgetTextLanguages[] $translations
 * @property WidgetTextLanguages   $activeTranslation
 */
class WidgetText extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_DRAFT = 0;

    /**
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     * @var WidgetTextLanguages[]
     */
    public $newTranslations = [];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%widget_text}}';
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
            'cacheInvalidate' => [
                'class' => CacheInvalidateBehavior::className(),
                'keys' => [
                    function ($model) {
                        return [
                            self::className(),
                            $model->key
                        ];
                    }
                ]
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key'], 'required'],
            [['key'], 'unique'],
            [['status'], 'integer'],
            [['key'], 'string', 'max' => 1024]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('i18ncontent', 'ID'),
            'key' => Yii::t('i18ncontent', 'Key'),
            'status' => Yii::t('i18ncontent', 'Status'),
            'created_at' => Yii::t('i18ncontent', 'Create Date'),
            'updated_at' => Yii::t('i18ncontent', 'Update Date')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(WidgetTextLanguages::className(), ['widget_text_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getActiveTranslation()
    {
        return $this->hasOne(WidgetTextLanguages::className(), ['widget_text_id' => 'id'])->where([
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

        $className = \yii\helpers\StringHelper::basename(\centigen\i18ncontent\models\WidgetTextLanguages::className());
        $translations = ArrayHelper::getValue($postData, $className);
        $this->newTranslations = [];

        $allValid = true;
        foreach ($translations as $loc => $modelData) {
            $modelData['locale'] = $loc;
            $modelData['body'] = Html::encodeMediaItemUrls($modelData['body']);

            $translation = $this->findTranslationByLocale($loc);

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
            $translation->widget_text_id = $this->id;
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
     * Find WidgetTranslation object from `translations` array by locale
     *
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     * @param $locale
     * @return WidgetTextLanguages|null
     */
    public function findTranslationByLocale($locale)
    {
        $translations = array_merge($this->newTranslations, $this->translations);
        foreach ($translations as $translation) {
            if ($translation->locale === $locale) {
                return $translation;
            }
        }

        return new \centigen\i18ncontent\models\WidgetTextLanguages();
    }

    /**
     * Find WidgetText by its key
     *
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     * @param             $key
     * @param null|boolean $status
     * @return WidgetText
     */
    public static function findByKey($key, $status = null)
    {
        $params = [
            'key' => $key
        ];
        if ($status !== null) {
            $params['status'] = intval($status);
        }

        return self::findOne($params);
    }

    public function getTitle()
    {
        return $this->activeTranslation ? $this->activeTranslation->title : '';
    }

    public function getBody()
    {
        return $this->activeTranslation ? $this->activeTranslation->body : '';
    }
}
