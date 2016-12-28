<?php

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Tabs;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model centigen\i18ncontent\models\WidgetCarouselItem */
/* @var $carousel centigen\i18ncontent\models\WidgetCarousel */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $translations array */
?>

<div class="widget-carousel-item-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php echo $form->errorSummary($model, ['class' => 'alert alert-danger']) ?>

    <?php echo $form->field($model, 'carousel_id', [
        'template' => '{input}',
    ])->hiddenInput(['value' => $carousel->id]) ?>

    <?php echo $form->field($model, 'image')->widget(
        \trntv\filekit\widget\Upload::className(),
        [
            'url' => ['upload'],
        ]
    ) ?>

    <?php echo $form->field($model, 'order')->textInput() ?>

    <?php echo $form->field($model, 'url')->textInput(['maxlength' => 1024]) ?>

    <?php

    if (isset($locales)) {
        $items = [];
        $ind = 0;
        foreach ($locales as $key => $locale) {
            $title = $locale;
            $translationModel = $model->findTranslationByLocale($key);

            $content = $this->render('_tab_content', [
                'form' => $form,
                'model' => $translationModel,
                'language' => $key,
            ]);

            $items[] = [
                'label' => $title,
                'content' => $content,
                'headerOptions' => [
                    'title' => $translationModel->hasErrors() ? Yii::t('i18ncontent', 'You have validation errors') : "",
                    'class' => $translationModel->hasErrors() ? 'has-error' : ''
                ],
                'options' => [
                    'class' => 'fade' . ($ind++ === 0 ? ' in' : '')
                ]
            ];
        }

        echo '<div class="tab-wrapper">';
        echo Tabs::widget([
            'items' => $items
        ]);
        echo '</div>';
    } else {
        echo $this->render('_tab_content', [
            'form' => $form,
            'model' => $model
        ]);
    }

    ?>

    <?php echo $form->field($model, 'status')->checkbox() ?>

    <div class="form-group">
        <?php echo Html::submitButton($model->isNewRecord ? Yii::t('i18ncontent', 'Create') : Yii::t('i18ncontent', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
