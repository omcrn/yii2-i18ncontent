<?php

namespace centigen\i18ncontent\models;

use centigen\base\behaviors\CacheInvalidateBehavior;
use trntv\filekit\behaviors\UploadBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;

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
 * @property WidgetCarouselItemTranslation[] $translations
 * @property WidgetCarouselItemTranslation $activeTranslation
 */
class WidgetCarouselItem extends TranslatableModel
{

    /**
     * @var array|null
     */
    public $image;

    public static $translateModelForeignKey = 'widget_carousel_item_id';

    public static $translateModel = WidgetCarouselItemTranslation::class;

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

//    public function scenarios()
//    {
//        $scenarios = parent::scenarios();
//        $key = array_search('carousel_id', $scenarios[self::SCENARIO_DEFAULT], true);
//        $scenarios[self::SCENARIO_DEFAULT][$key] = '!carousel_id';
//        return $scenarios;
//    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            [
                'class' => UploadBehavior::className(),
                'attribute' => 'image',
                'pathAttribute' => 'path',
                'baseUrlAttribute' => null,
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
        return $this->hasMany(WidgetCarouselItemTranslation::className(), ['widget_carousel_item_id' => 'id']);
    }

    public function getActiveTranslation()
    {
        return $this->hasOne(WidgetCarouselItemTranslation::className(), ['widget_carousel_item_id' => 'id'])->where([
            'locale' => Yii::$app->language
        ]);
    }

    /**
     * @return string
     */
    public function getImageUrl()
    {
        return Yii::getAlias('@storageUrl') . '/source/' . ltrim($this->path, '/');
    }

}
