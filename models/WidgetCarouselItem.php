<?php

namespace centigen\i18ncontent\models;

use centigen\base\behaviors\CacheInvalidateBehavior;
use trntv\filekit\behaviors\UploadBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\Exception;

/**
 * This is the model class for table "widget_carousel_item".
 *
 * @property integer $id
 * @property integer $carousel_id
 * @property string $base_url
 * @property string $path
 * @property string $type
 * @property string $image
 * @property string $imageUrl
 * @property string $url
 * @property integer $status
 * @property integer $order
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property WidgetCarousel $carousel
 * @property WidgetCarouselItemLanguages[] $translations
 * @property WidgetCarouselItemLanguages $activeTranslation
 */
class WidgetCarouselItem extends \yii\db\ActiveRecord
{

    /**
     * @var array|null
     */
    public $image;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%widget_carousel_item}}';
    }

    /**
     * @inheritdoc
     * @return ActiveQuery the newly created [[ActiveQuery]] instance.
     */
    public static function find()
    {
        return parent::find()->with('activeTranslation');
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $key = array_search('carousel_id', $scenarios[self::SCENARIO_DEFAULT], true);
        $scenarios[self::SCENARIO_DEFAULT][$key] = '!carousel_id';
        return $scenarios;
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            [
                'class' => UploadBehavior::className(),
                'attribute' => 'image',
                'pathAttribute' => 'path',
                'baseUrlAttribute' => 'base_url',
                'typeAttribute' => 'type'
            ],
            'cacheInvalidate'=>[
                'class' => CacheInvalidateBehavior::className(),
                'keys' => [
                    function ($model) {
                        return [
                            WidgetCarousel::className(),
                            $model->carousel->key
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
            [['carousel_id'], 'required'],
            [['carousel_id', 'status', 'order'], 'integer'],
            [['url', 'base_url', 'path'], 'string', 'max' => 1024],
            [['type'], 'string', 'max' => 45],
            ['image', 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('i18ncontent', 'ID'),
            'carousel_id' => Yii::t('i18ncontent', 'Carousel ID'),
            'image' => Yii::t('i18ncontent', 'Image'),
            'base_url' => Yii::t('i18ncontent', 'Base URL'),
            'path' => Yii::t('i18ncontent', 'Path'),
            'type' => Yii::t('i18ncontent', 'File Type'),
            'url' => Yii::t('i18ncontent', 'Url'),
            'status' => Yii::t('i18ncontent', 'Status'),
            'order' => Yii::t('i18ncontent', 'Order')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCarousel()
    {
        return $this->hasOne(WidgetCarousel::className(), ['id' => 'carousel_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(WidgetCarouselItemLanguages::className(), ['widget_carousel_item_id' => 'id']);
    }

    public function getActiveTranslation()
    {
        return $this->hasOne(WidgetCarouselItemLanguages::className(), ['widget_carousel_item_id' => 'id'])->where([
            'locale' => Yii::$app->language
        ]);
    }

    /**
     * @return string
     */
    public function getImageUrl()
    {
        return rtrim($this->base_url, '/') . '/' . ltrim($this->path, '/');
    }

    /**
     * Save widget with given data.
     *
     * @author zura
     * @param array $data data to insert.
     *
     * The following options are specially handled:
     *
     * - valid attributes of WidgetCarouselItem class
     * - translations: Array of arrays where each sub array is in the following format
     *
     * ~~~
     * [
     *      'caption' => '...',
     *      'locale'  => '...'
     * ]
     * ~~~
     *
     * @return bool
     * @throws Exception
     */
    public static function saveWidget($data)
    {
        $transaction = Yii::$app->db->beginTransaction();
//        \ChromePhp::log($data);
        $model = new self();
        $model->attributes = $data;
        $model->carousel_id = intval($data['carousel_id']);
        try {

            if (!$model->validate() || !$model->save()) {
//                \ChromePhp::error($model->errors);
                return false;
            }

            foreach ($data['translations'] as $item) {
//                \ChromePhp::log($model, $item['caption'], $item['locale']);
                $translation = new WidgetCarouselItemLanguages($model, $item['caption'], $item['locale']);
                if (!$translation->validate() || !$translation->save()) {
                    $transaction->rollBack();
//                    \ChromePhp::error($translation->errors);
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

    /**
     * Update WidgetCarouselItem with its translations
     *
     * @author zura
     * @param WidgetCarouselItem $widget
     * @param WidgetCarouselItemLanguages[] $translations
     * @param array $data Assumes to contain key - value pairs. Data to update WidgetCarouselItem
     *
     * The following options are specially handled:
     *
     * - valid attributes of WidgetCarouselItem class
     * - translations: Array of arrays, where each sub array key is $locale and value is $caption string
     *
     * @return bool
     * @throws Exception
     */
    public static function updateWidget(WidgetCarouselItem $widget, $translations, $data)
    {
        $transaction = Yii::$app->db->beginTransaction();

        $widget->attributes = $data;
        try {

            if (!$widget->validate() || !$widget->save()) {
//                \ChromePhp::error($widget->errors);
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
                    $currentTrans = new WidgetCarouselItemLanguages();
                }

                $currentTrans->widget_carousel_item_id = $widget->id;
                $currentTrans->caption = $item;
                $currentTrans->locale = $loc;

                if (!$currentTrans->validate() || !$currentTrans->save()) {
//                    \ChromePhp::error($currentTrans->errors);
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
