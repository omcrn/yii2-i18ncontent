<?php

/* @var $this yii\web\View */
/* @var $carousel centigen\i18ncontent\models\WidgetCarousel */
/* @var $model centigen\i18ncontent\models\WidgetCarouselItem */
/* @var $locales array */

$this->title = Yii::t('i18ncontent', 'Update {modelClass}: ', [
    'modelClass' => 'Widget Carousel Item',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('i18ncontent', 'Widget Carousel Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->carousel->key, 'url' => ['update', 'id' => $model->carousel->id]];
$this->params['breadcrumbs'][] = Yii::t('i18ncontent', 'Update');
?>
<div class="widget-carousel-item-update">

    <?php echo $this->render('_form', [
        'model' => $model,
        'locales' => $locales,
        'carousel' => $carousel
    ]) ?>

</div>
