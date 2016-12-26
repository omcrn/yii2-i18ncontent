<?php
/* @var $this yii\web\View */
/* @var $model centigen\i18ncontent\models\WidgetCarousel */

$this->title = Yii::t('i18ncontent', 'Create {modelClass}', [
    'modelClass' => 'Widget Carousel',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('i18ncontent', 'Widget Carousels'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="widget-carousel-create">

    <?php echo $this->render('_form', [
        'model' => $model
    ]) ?>

</div>
