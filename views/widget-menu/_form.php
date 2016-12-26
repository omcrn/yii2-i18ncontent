<?php
/**
 * User: zura
 * Date: 10/27/2016
 * Time: 10:34 AM
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
/* @var $this yii\web\View */
/* @var $model \centigen\i18ncontent\models\WidgetMenu */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div class="widget-menu-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php echo $form->errorSummary($model) ?>

    <?php echo $form->field($model, 'key')->textInput(['maxlength' => 1024]) ?>

    <?php echo $form->field($model, 'title')->textInput(['maxlength' => 512]) ?>

    <?php echo $form->field($model, 'items')->widget(
        \centigen\aceeditor\AceEditor::className(),
        [
            'mode' => 'json'
        ]
    ) ?>

    <?php echo $form->field($model, 'status')->checkbox() ?>

    <div class="form-group">
        <?php echo Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>