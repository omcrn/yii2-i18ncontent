<?php

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Tabs;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model centigen\i18ncontent\models\WidgetCarouselItem */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $translations array */
?>

<div class="widget-carousel-item-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php echo $form->errorSummary($model) ?>

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
            $content = $this->render('_tab_content', [
                'form' => $form,
                'model' => isset($translations) && is_array($translations) && isset($translations[$ind]) ?
                                    $translations[$ind] : new \centigen\i18ncontent\models\WidgetCarouselItemLanguages(),
                'language' => $key,
            ]);

            $items[] = [
                'label' => $title,
                'content' => $content,
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
