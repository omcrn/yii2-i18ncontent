<?php

/* @var $this yii\web\View */
/* @var $model centigen\i18ncontent\models\WidgetTextLanguages */
/* @var $form yii\bootstrap\ActiveForm */

$namePrefix = 'ArticleCategoryTranslations';
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
