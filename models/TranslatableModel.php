<?php
/**
 * User: zura
 * Date: 12/28/16
 * Time: 3:29 PM
 */

namespace centigen\i18ncontent\models;

use centigen\base\helpers\DateHelper;
use centigen\i18ncontent\helpers\Html;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;


/**
 * Class TranslatableModel
 *
 * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
 * @package omcrn\i18ncontent\models
 *
 * @property ActiveRecord|null $activeTranslation
 * @property ActiveRecord[]|array $translations
 */
class TranslatableModel extends ActiveRecord
{
    public $newTranslations = [];

    public static $translateModelForeignKey = null;

    public static $translateModel = null;

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(static::$translateModel, [static::$translateModelForeignKey => 'id']);
    }

    /**
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     * @return \yii\db\ActiveQuery
     */
    public function getActiveTranslation()
    {
        return $this->hasOne(static::$translateModel, [static::$translateModelForeignKey => 'id'])->where([
            'locale' => Yii::$app->language
        ]);
    }

    /**
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     * @inheritdoc
     */
    public function load($postData, $formName = null)
    {
        $scope = $formName === null ? $this->formName() : $formName;
        $myData = ArrayHelper::getValue($postData, $scope);

        if (isset($myData['published_at']) && $myData['published_at']) {
            $myData['published_at'] = DateHelper::fromFormatIntoMysql(DateHelper::$yiiFormatToPhpMapping[\Yii::$app->formatter->datetimeFormat], $myData['published_at']);
            $myData['published_at'] = DateHelper::fromMySqlIntoTimestamp($myData['published_at']);
            $postData[$scope] = $myData;
        }
        if (!parent::load($postData, $formName)) {
            return false;
        }

        $className = \yii\helpers\StringHelper::basename(static::$translateModel);
        $translations = ArrayHelper::getValue($postData, $className);
        $this->newTranslations = [];

        $allValid = true;
        if(!empty($translations)){
            foreach ($translations as $loc => $modelData) {
                $modelData['locale'] = $loc;
                if (isset($modelData['body'])) {
                    $modelData['body'] = Html::encodeMediaItemUrls($modelData['body']);
                }
                if (isset($modelData['extra_description'])) {
                    $modelData['extra_description'] = Html::encodeMediaItemUrls($modelData['extra_description']);
                }
                if (isset($modelData['short_description']) &&
                    ($this->hasAttribute('short_description') || $this->hasProperty('short_description'))) {
                    $this ->short_description = Html::encodeMediaItemUrls($modelData['short_description']);
                }

                if (Yii::$app->language === $loc && isset($modelData['title']) &&
                    ($this->hasAttribute('title') || $this->hasProperty('title'))
                ) {
                    $this->title = $modelData['title'];
                }
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
            $translation->{static::$translateModelForeignKey} = $this->id;
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
     * @return ActiveRecord
     */
    public function findTranslationByLocale($locale)
    {
        $translations = array_merge($this->newTranslations, $this->translations);
        foreach ($translations as $translation) {
            if ($translation->locale === $locale) {
                return $translation;
            }
        }

        return new static::$translateModel();
    }
}
