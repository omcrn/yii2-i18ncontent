<?php

/* @var $this yii\web\View */
use yii\helpers\Url;

/* @var $model centigen\i18ncontent\models\ArticleTranslations */
/* @var $form yii\bootstrap\ActiveForm */

$namePrefix = 'ArticleTranslations';
$nameSuffix = '';
if (isset($language)) {
    $nameSuffix = '[' . $language . ']';
}

?>
<?php echo $form->field($model, 'title', [
    'inputOptions' => [
        'name' => $namePrefix . '[title]' . $nameSuffix
    ]
])->textInput(['maxlength' => true]) ?>

<?php echo $form->field($model, 'body')->widget(
    \yii\imperavi\Widget::className(),
    [
        'plugins' => ['fullscreen', 'fontcolor', 'video'],
        'htmlOptions' => [
            'name' => $namePrefix . '[body]' . $nameSuffix,
            'value' => $model->getBody()
        ],
        'options' => [
            'minHeight' => 400,
            'maxHeight' => 400,
            'buttonSource' => true,
            'convertDivs' => false,
            'removeEmptyTags' => false,
            'replaceDivs' => false,
            'imageUpload' => Url::to(['upload-imperavi'])
        ]
    ]
) ?>

<?php echo $form->field($model, 'meta_title', [
    'inputOptions' => [
        'name' => $namePrefix . '[meta_title]' . $nameSuffix
    ]
])->textarea(['maxlength' => 512]) ?>


<?php echo $form->field($model, 'meta_keywords', [
    'inputOptions' => [
        'name' => $namePrefix . '[meta_keywords]' . $nameSuffix
    ]
])->textarea(['maxlength' => 512]) ?>


<?php echo $form->field($model, 'meta_description', [
    'inputOptions' => [
        'name' => $namePrefix . '[meta_description]' . $nameSuffix
    ]
])->textarea(['maxlength' => 512]) ?>
