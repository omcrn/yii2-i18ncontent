<?php

/* @var $this yii\web\View */
/* @var $model centigen\i18ncontent\models\WidgetCarouselItem */
/* @var $translations centigen\i18ncontent\models\WidgetCarouselItemLanguages */
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
        'translations' => $translations,
        'locales' => $locales
    ]) ?>

</div>
