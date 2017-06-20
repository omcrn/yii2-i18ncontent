<?php

namespace centigen\i18ncontent\models;

use centigen\base\behaviors\CacheInvalidateBehavior;
use centigen\i18ncontent\models\query\WidgetTextQuery;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "text_block".
 *
 * @property integer               $id
 * @property string                $key
 * @property integer               $status
 * @property integer               $created_at
 * @property integer               $updated_at
 *
 * @property WidgetTextTranslation[] $translations
 * @property WidgetTextTranslation   $activeTranslation
 */
class WidgetText extends TranslatableModel
{
    const STATUS_ACTIVE = 1;
    const STATUS_DRAFT = 0;

    /**
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     * @var WidgetTextTranslation[]
     */
    public $newTranslations = [];

    public static $translateModelForeignKey = 'widget_text_id';

    public static $translateModel = WidgetTextTranslation::class;

    public $title = null;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%widget_text}}';
    }

    /**
     * @return WidgetTextQuery
     */
    public static function find()
    {
        return (new WidgetTextQuery(get_called_class()))->with('activeTranslation');
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
        return $this->activeTranslation ? $this->activeTranslation->getBody() : '';
    }
}
