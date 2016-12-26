<?php

namespace centigen\i18ncontent\models;

use centigen\base\behaviors\CacheInvalidateBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\Exception;

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

    public function getActiveTranslation()
    {
        return $this->hasOne(WidgetTextLanguages::className(), ['widget_text_id' => 'id'])->where([
            'locale' => Yii::$app->language
        ]);
    }

    /**
     * Save widget with given data.
     *
     * @author zura
     * @param array $data data to insert.
     *
     * The following options are specially handled:
     *
     * - key: the key of WidgetText
     * - status: the status of WidgetText
     * - translations: Array of arrays where each sub array is in the following format
     *
     * ~~~
     * [
     *      'title' => '...',
     *      'body' => '...',
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

        $widgetText = new self();

        $widgetText->key = $data['key'];
        $widgetText->status = $data['status'];

        try {

            if (!$widgetText->validate() || !$widgetText->save()) {
//                \ChromePhp::error($widgetText->errors);
                return false;

            }

            foreach ($data['translations'] as $item) {
                $text = new WidgetTextLanguages();
                $text->widget_text_id = $widgetText->id;
                $text->title = $item['title'];
                $text->body = $item['body'];
                $text->locale = $item['locale'];
                if (!$text->validate() || !$text->save()) {
                    $transaction->rollBack();

//                    \ChromePhp::error($text->errors);
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
     * Update WidgetText with its translations
     *
     * @author zura
     * @param WidgetText            $widget
     * @param WidgetTextLanguages[] $translations
     * @param array                 $data
     *
     * The following options are specially handled:
     *
     * - key: the key of WidgetText
     * - status: the status of WidgetText
     * - translations: Array of arrays where each sub array key is $locale and value is array of the following format
     *
     * ~~~
     * [
     *      'title' => '...',
     *      'body' => '...'
     * ]
     * ~~~
     *
     * @return bool
     * @throws Exception
     */
    public static function updateWidget(WidgetText $widget, $translations, $data)
    {
        $transaction = Yii::$app->db->beginTransaction();

        $widget->key = $data['key'];
        $widget->status = $data['status'];

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
                    $currentTrans = new WidgetTextLanguages();
                }

                $currentTrans->widget_text_id = $widget->id;
                $currentTrans->title = $item['title'];
                $currentTrans->body = $item['body'];
                $currentTrans->locale = $loc;

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
