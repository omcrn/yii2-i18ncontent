<?php

use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $language string */
/* @var $model centigen\i18ncontent\models\I18nMessage */
/* @var $form yii\bootstrap\ActiveForm */

$className = \yii\helpers\StringHelper::basename(\centigen\i18ncontent\models\I18nMessage::className());

?>
<?php echo $form->field($model, 'translation', [
    'inputOptions' => [
        'name' => "{$className}[$language][translation]"
    ]
])->textarea(['maxlength' => 512]) ?>
