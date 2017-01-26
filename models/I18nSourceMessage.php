<?php

namespace centigen\i18ncontent\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

/**
 * This is the model class for table "{{%i18n_source_message}}".
 *
 * @property integer $id
 * @property string $category
 * @property string $message
 *
 * @property I18nMessage[] $i18nMessages
 * @property ActiveRecord[]|array $translations
 */
class I18nSourceMessage extends ActiveRecord
{
    public $newTranslations = [];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%source_message}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['message'], 'string'],
            [['category'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('i18ncontent', 'ID'),
            'category' => Yii::t('i18ncontent', 'Category'),
            'message' => Yii::t('i18ncontent', 'Message'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getI18nMessages()
    {
        return $this->hasMany(I18nMessage::className(), ['id' => 'id']);
    }


    /**
     * @author Guga Grigolia <grigolia.guga@gmail.com>
     * @inheritdoc
     */
    public function load($postData, $formName = null)
    {
        if (!parent::load($postData, $formName)) {
            return false;
        }

        $className = StringHelper::basename(I18nMessage::className());
        $translations = ArrayHelper::getValue($postData, $className);
        $this->newTranslations = [];

        $allValid = true;
        if(!empty($translations)){
            foreach ($translations as $loc => $modelData) {
                $modelData['language'] = $loc;


                $translation = $this->findTranslationByLocale($loc);

                $this->newTranslations[] = $translation;
                if (!$translation->load($modelData, '')) {
                    $allValid = false;
                }
            }
        }

        return $allValid;
    }

    /**
     * Find PageTranslation object from `translations` array by locale
     *
     * @author Guga Grigolia <grigolia.guga@gmail.com>
     * @param $locale
     * @return ActiveRecord
     */
    public function findTranslationByLocale($locale)
    {
        $translations = array_merge($this->newTranslations, $this->i18nMessages);
        foreach ($translations as $translation) {
            if ($translation->locale === $locale) {
                return $translation;
            }
        }

        return new I18nMessage();
    }
}
