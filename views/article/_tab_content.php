<?php

use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $language string */
/* @var $model centigen\i18ncontent\models\ArticleTranslation */
/* @var $form yii\bootstrap\ActiveForm */

$className = \yii\helpers\StringHelper::basename(\centigen\i18ncontent\models\ArticleTranslation::className());

?>
<?php echo $form->field($model, 'title', [
    'inputOptions' => [
        'name' => "{$className}[$language][title]"
    ]
])->textInput(['maxlength' => true]) ?>

<?php echo $form->field($model, 'keywords', [
    'inputOptions' => [
        'name' => "{$className}[$language][keywords]"
    ]
])->textInput(['maxlength' => true]) ?>

<?php echo $form->field($model, 'short_description')->widget(
    \yii\imperavi\Widget::className(),
    [
        'plugins' => ['table', 'fullscreen', 'fontcolor', 'video'],
        'htmlOptions' => [
            'name' => "{$className}[$language][short_description]",
//            'name' => $namePrefix . '[body]' . $nameSuffix,
            'value' => $model->getShortDescription()
        ],
        'options' => [
            'minHeight' => 100,
            'maxHeight' => 200,
            'buttonSource' => true,
            'convertDivs' => false,
            'removeEmptyTags' => false,
            'replaceDivs' => false,
            'imageUpload' => Url::to(['upload-imperavi'])
        ]
    ]
) ?>
<?php echo $form->field($model, 'body')->widget(
    \yii\imperavi\Widget::className(),
    [
        'plugins' => ['table', 'fullscreen', 'fontcolor', 'video'],
        'htmlOptions' => [
            'name' => "{$className}[$language][body]",
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
        'name' => "{$className}[$language][meta_title]"
    ]
])->textarea(['maxlength' => 512]) ?>


<?php echo $form->field($model, 'meta_keywords', [
    'inputOptions' => [
        'name' => "{$className}[$language][meta_keywords]"
    ]
])->textarea(['maxlength' => 512]) ?>


<?php echo $form->field($model, 'meta_description', [
    'inputOptions' => [
        'name' => "{$className}[$language][meta_description]"
    ]
])->textarea(['maxlength' => 512]) ?>
