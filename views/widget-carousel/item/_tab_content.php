<?php

use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $language string */
/* @var $model centigen\i18ncontent\models\WidgetCarouselItemTranslation */
/* @var $form yii\bootstrap\ActiveForm */

$className = \yii\helpers\StringHelper::basename(\centigen\i18ncontent\models\WidgetCarouselItemTranslation::className());

?>

<?php echo $form->field($model, 'caption')->widget(
    \yii\imperavi\Widget::className(), [
        'plugins' => ['table', 'fullscreen', 'fontcolor', 'video'],
        'htmlOptions' => [
            'name' => "{$className}[$language][caption]"
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

