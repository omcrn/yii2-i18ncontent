<?php
/**
 * User: zura
 * Date: 10/27/2016
 * Time: 10:41 AM
 */

use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model \centigen\i18ncontent\models\WidgetMenu */
$this->title = Yii::t('i18ncontent', 'Update {modelClass}: ', [
    'modelClass' => 'Widget Menu',
]) . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('i18ncontent', 'Widget Menus'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('i18ncontent', 'Update');
?>
<div class="widget-menu-update">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>