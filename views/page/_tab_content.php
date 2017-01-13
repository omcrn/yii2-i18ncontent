<?php

use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $language string */
/* @var $model centigen\i18ncontent\models\PageTranslation */
/* @var $form yii\bootstrap\ActiveForm */

$className = \yii\helpers\StringHelper::basename(\centigen\i18ncontent\models\PageTranslation::className());

?>

<?php echo $form->field($model, 'title', [
    'inputOptions' => [
        'name' => "{$className}[$language][title]"
    ]
])->textInput(['maxlength' => 512]) ?>

<?php echo $form->field($model, 'body')->widget(\yii\imperavi\Widget::className(), [
    // More options, see http://imperavi.com/redactor/docs/
    'plugins' => ['fullscreen', 'fontcolor', 'video', 'table'],
    'htmlOptions' => [
        'name' => "{$className}[$language][body]",
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
]) ?>


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
