<?php

use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $language string */
/* @var $model centigen\i18ncontent\models\WidgetTextTranslation */
/* @var $form yii\bootstrap\ActiveForm */

$className = \yii\helpers\StringHelper::basename(\centigen\i18ncontent\models\WidgetTextTranslation::className());

?>

<?php echo $form->field($model, 'title', [
    'inputOptions' => [
        'name' => "{$className}[$language][title]"
    ]
])->textInput(['maxlength' => 512]) ?>

<?php echo $form->field($model, 'body')->widget(\yii\imperavi\Widget::className(), [
    // More options, see http://imperavi.com/redactor/docs/
    'plugins' => ['fullscreen', 'fontcolor', 'video'],
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