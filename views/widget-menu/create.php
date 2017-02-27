<?php
/**
 * User: zura
 * Date: 10/27/2016
 * Time: 10:40 AM
 */
/* @var $this yii\web\View */
/* @var $model \centigen\i18ncontent\models\WidgetMenu */
$this->title = Yii::t('i18ncontent', 'Create {modelClass}', [
    'modelClass' => 'Widget Menu',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('i18ncontent', 'Widget Menus'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="widget-menu-create">
    <a class="pull-right" data-target="#options-modal" data-toggle="modal"><i class="fa fa-cogs"></i><?php echo Yii::t('i18ncontent', 'Menu options')?></a>
    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>