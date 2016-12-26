<?php
/**
 * User: zura
 * Date: 10/27/2016
 * Time: 10:40 AM
 */
/* @var $this yii\web\View */
/* @var $model \centigen\i18ncontent\models\WidgetMenu */
$this->title = Yii::t('backend', 'Create {modelClass}', [
    'modelClass' => 'Widget Menu',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Widget Menus'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="widget-menu-create">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>