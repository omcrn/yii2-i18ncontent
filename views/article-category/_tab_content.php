<?php

/* @var $this yii\web\View */
/* @var $language string */
/* @var $model centigen\i18ncontent\models\ArticleCategoryTranslation */
/* @var $form yii\bootstrap\ActiveForm */

$className = \yii\helpers\StringHelper::basename(\centigen\i18ncontent\models\ArticleCategoryTranslation::className());

?>

<?php echo $form->field($model, 'title', [
    'inputOptions' => [
        'name' => "{$className}[$language][title]"
    ]
])->textInput(['maxlength' => 512]) ?>
