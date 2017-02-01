<?php

namespace centigen\i18ncontent\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%article_attachment}}".
 *
 * @property integer $id
 * @property integer $article_id
 * @property string $base_url
 * @property string $path
 * @property string $url
 * @property string $name
 * @property string $type
 * @property string $size
 *
 * @property Article $article
 */
class ArticleAttachment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%article_attachment}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'updatedAtAttribute' => false
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['article_id', 'path'], 'required'],
            [['article_id', 'size'], 'integer'],
            [['base_url', 'path', 'type', 'name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('i18ncontent', 'ID'),
            'article_id' => Yii::t('i18ncontent', 'Article ID'),
            'base_url' => Yii::t('i18ncontent', 'Base Url'),
            'path' => Yii::t('i18ncontent', 'Path'),
            'size' => Yii::t('i18ncontent', 'Size'),
            'type' => Yii::t('i18ncontent', 'Type'),
            'name' => Yii::t('i18ncontent', 'Name')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticle()
    {
        return $this->hasOne(Article::className(), ['id' => 'article_id']);
    }

    public function getUrl()
    {
        return Yii::getAlias('@storageUrl') . '/source/' . $this->path;
    }
}
