<?php
/** @var $this yii\web\View
 * @var $model centigen\i18ncontent\models\WidgetCarouselItem
 * @var $carousel centigen\i18ncontent\models\WidgetCarousel
 * @var $locales array
 */

$this->title = Yii::t('i18ncontent', 'Create {modelClass}', [
    'modelClass' => 'Widget Carousel Item',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('i18ncontent', 'Widget Carousel Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $carousel->key, 'url' => ['update', 'id' => $carousel->id]];
$this->params['breadcrumbs'][] = Yii::t('i18ncontent', 'Create');
?>
<div class="widget-carousel-item-create">

    <?php echo $this->render('_form', [
        'model' => $model,
        'locales' => $locales,
        'carousel' => $carousel
    ]) ?>

</div>
