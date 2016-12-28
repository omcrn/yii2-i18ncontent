<?php

use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $language string */
/* @var $model centigen\i18ncontent\models\ArticleTranslations */
/* @var $form yii\bootstrap\ActiveForm */

$namePrefix = \yii\helpers\StringHelper::basename(\centigen\i18ncontent\models\ArticleTranslations::className());

?>
<?php echo $form->field($model, 'title', [
    'inputOptions' => [
        'name' => "{$namePrefix}[$language][title]"
    ]
])->textInput(['maxlength' => true]) ?>

<?php echo $form->field($model, 'body')->widget(
    \yii\imperavi\Widget::className(),
    [
        'plugins' => ['fullscreen', 'fontcolor', 'video'],
        'htmlOptions' => [
            'name' => "{$namePrefix}[$language][body]",
//            'name' => $namePrefix . '[body]' . $nameSuffix,
            'value' => $model->getBody()
        ],
        'options' => [
            'minHeight' => 400,
            'maxHeight' => 400,
            'buttonSource' => true,
            'convertDivs' => false,
            'removeEmptyTags' => false,
            'replaceDivs' => false,
            'imageUpload' => Url::to(['upload-imperavi'])
        ]
    ]
) ?>

<?php echo $form->field($model, 'meta_title', [
    'inputOptions' => [
        'name' => "{$namePrefix}[$language][meta_title]"
    ]
])->textarea(['maxlength' => 512]) ?>


<?php echo $form->field($model, 'meta_keywords', [
    'inputOptions' => [
        'name' => "{$namePrefix}[$language][meta_keywords]"
    ]
])->textarea(['maxlength' => 512]) ?>


<?php echo $form->field($model, 'meta_description', [
    'inputOptions' => [
        'name' => "{$namePrefix}[$language][meta_description]"
    ]
])->textarea(['maxlength' => 512]) ?>
