<?php

namespace centigen\i18ncontent\models;

use Yii;

/**
 * This is the model class for table "{{%i18n_message}}".
 *
 * @property integer $id
 * @property string $language
 * @property string $translation
 * @property string $sourceMessage
 * @property string $category
 *
 * @property I18nSourceMessage $sourceMessageModel
 */
class I18nMessage extends \yii\db\ActiveRecord
{
    public $category;
    public $sourceMessage;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%message}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'language'], 'required'],
            [['id'], 'exist', 'targetClass'=>I18nSourceMessage::className(), 'targetAttribute'=>'id'],
            [['translation'], 'string'],
            [['language'], 'string', 'max' => 16],
            [['language'], 'unique', 'targetAttribute' => ['id', 'language']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('i18ncontent', 'ID'),
            'language' => Yii::t('i18ncontent', 'Language'),
            'translation' => Yii::t('i18ncontent', 'Translation'),
            'sourceMessage' => Yii::t('i18ncontent', 'Source Message'),
            'category' => Yii::t('i18ncontent', 'Category'),
        ];
    }

    public function afterFind()
    {
        $this->sourceMessage = $this->sourceMessageModel ? $this->sourceMessageModel->message : null;
        $this->category = $this->sourceMessageModel ? $this->sourceMessageModel->category : null;
        return parent::afterFind();
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSourceMessageModel()
    {
        return $this->hasOne(I18nSourceMessage::className(), ['id' => 'id']);
    }
}
