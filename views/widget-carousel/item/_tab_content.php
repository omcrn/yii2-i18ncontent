<?php

/* @var $this yii\web\View */
use yii\helpers\Url;

/* @var $model centigen\i18ncontent\models\WidgetCarouselItemLanguages */
/* @var $form yii\bootstrap\ActiveForm */

$namePrefix = 'WidgetCarouselItemLanguages';
$nameSuffix = '';
if (isset($language)) {
    $nameSuffix = '[' . $language . ']';
}

?>

<?php echo $form->field($model, 'caption')->widget(
    \yii\imperavi\Widget::className(), [
        'plugins' => ['fullscreen', 'fontcolor', 'video'],
        'htmlOptions' => [
            'name' => $namePrefix . '[caption]' . $nameSuffix
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

