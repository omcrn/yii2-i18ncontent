<?php

/* @var $this yii\web\View */
/* @var $language string */
/* @var $model centigen\i18ncontent\models\ArticleCategoryTranslations */
/* @var $form yii\bootstrap\ActiveForm */

$namePrefix = \yii\helpers\StringHelper::basename(\centigen\i18ncontent\models\ArticleCategoryTranslations::className());;

?>

<?php echo $form->field($model, 'title', [
    'inputOptions' => [
        'name' => "{$namePrefix}[$language][title]"
    ]
])->textInput(['maxlength' => 512]) ?>
