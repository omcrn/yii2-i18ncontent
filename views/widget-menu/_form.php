<?php
/**
 * User: zura
 * Date: 10/27/2016
 * Time: 10:34 AM
 */

use trntv\aceeditor\AceEditor;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
/* @var $this yii\web\View */
/* @var $model \centigen\i18ncontent\models\WidgetMenu */
/* @var $form yii\bootstrap\ActiveForm */

\centigen\i18ncontent\IconPickerAssetBundle::register($this);
$menuItems = \yii\helpers\Json::decode($model->items);
$base_options = \yii\helpers\Json::decode($model->base_options);
//var_dump($base_options);exit;
//$this->registerJs("");
?>

<div class="i18ncontent-form widget-menu-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php echo $form->errorSummary($model) ?>

    <?php echo $form->field($model, 'key')->textInput(['maxlength' => 1024]) ?>

    <?php echo $form->field($model, 'title')->textInput(['maxlength' => 512]) ?>

    <h3><?php echo Yii::t('i18ncontent', 'Menu items')?></h3>

    <?php echo $this->render('_items', ['items' => $menuItems, 'form' => $form, 'model' =>  $model])?>
    <?php echo $this->render('_options_modal', ['base_options' => $base_options, 'form' => $form, 'model' =>  $model])?>

    <?php echo $form->field($model, 'status')->checkbox([
        'template' => '<div class="om-checkbox"><label style="padding-left: 0;">'.$model->getAttributeLabel('status').' {input}<span class="om-checkbox-material"><span class="check"></span></span></label></div>{hint}{error}'
    ]) ?>

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