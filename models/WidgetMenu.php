<?php
namespace centigen\i18ncontent\models;
use centigen\base\behaviors\CacheInvalidateBehavior;
use centigen\base\validators\JsonValidator;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "widget_menu".
 *
 * @property integer $id
 * @property string $key
 * @property string $title
 * @property string $items
 * @property integer $status
 */
class WidgetMenu extends ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_DRAFT = 0;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%widget_menu}}';
    }
    public function behaviors()
    {
        return [
            'cacheInvalidate' => [
                'class' => CacheInvalidateBehavior::className(),
                'cacheComponent' => 'frontendCache',
                'keys' => [
                    function ($model) {
                        return [
                            get_class($model),
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
            [['key', 'title', 'items'], 'required'],
            [['key'], 'unique'],
            [['items'], JsonValidator::className()],
            [['status'], 'integer'],
            [['key'], 'string', 'max' => 32],
            [['title'], 'string', 'max' => 255]
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
            'title' => Yii::t('i18ncontent', 'Title'),
            'items' => Yii::t('i18ncontent', 'Config'),
            'status' => Yii::t('i18ncontent', 'Status')
        ];
    }
}