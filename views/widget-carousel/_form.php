<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model centigen\i18ncontent\models\WidgetCarousel */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="i18ncontent-form widget-carousel-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php echo $form->field($model, 'key')->textInput(['maxlength' => 1024]) ?>

    <?php echo $form->field($model, 'status')->checkbox() ?>

    <div class="form-group pull-left margin-right-5">
        <?php echo Html::submitButton($model->isNewRecord ? Yii::t('i18ncontent', 'Create') : Yii::t('i18ncontent', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php if(!$model->isNewRecord): ?>
        <div class="form-group">
            <?php echo Html::a(Yii::t('i18ncontent', 'Delete', []), ['delete', 'id' => $model->id],
                [
                    'class' => 'btn btn-danger',
                    'data-confirm' => "Are you sure you want to delete this item?",
                    'data-method'=>"post",
                    'data-pjax' => "0"
                ]) ?>
        </div>
    <?php endif?>

</div>
