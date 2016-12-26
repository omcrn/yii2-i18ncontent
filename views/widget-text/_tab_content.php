<?php

/* @var $this yii\web\View */
use yii\helpers\Url;

/* @var $model centigen\i18ncontent\models\WidgetTextLanguages */
/* @var $form yii\bootstrap\ActiveForm */

$namePrefix = 'WidgetTextLanguages';
$nameSuffix = '';
if (isset($language)) {
    $nameSuffix = '[' . $language . ']';
}

?>

<?php echo $form->field($model, 'title', [
    'inputOptions' => [
        'name' => $namePrefix . '[title]' . $nameSuffix
    ]
])->textInput(['maxlength' => 512]) ?>

<?php echo $form->field($model, 'body')->widget(\yii\imperavi\Widget::className(), [
    // More options, see http://imperavi.com/redactor/docs/
    'plugins' => ['fullscreen', 'fontcolor', 'video'],
    'htmlOptions' => [
        'name' => $namePrefix . '[body]' . $nameSuffix
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