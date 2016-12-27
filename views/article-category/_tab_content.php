<?php

/* @var $this yii\web\View */
/* @var $model centigen\i18ncontent\models\ArticleCategoryTranslations */
/* @var $form yii\bootstrap\ActiveForm */

\centigen\base\helpers\UtilHelper::vardump($language);
$namePrefix = 'ArticleCategoryTranslations';
$nameSuffix = '';
if (isset($language)) {
    $nameSuffix = '[' . $language . ']';
}

?>

<?php echo $form->field($model, 'title', [
    'inputOptions' => [
        'name' => "$language[$namePrefix][title]"
    ]
])->textInput(['maxlength' => 512]) ?>
