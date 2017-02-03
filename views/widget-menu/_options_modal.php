<?php
/**
 * Created by PhpStorm.
 * User: koco
 * Date: 2/2/2017
 * Time: 1:07 PM
 * @var $items array
 * @var $form yii\bootstrap\ActiveForm
 * @var $model \centigen\i18ncontent\models\WidgetMenu
 */
$html = '';
if(!function_exists('generateMenuItems')){
    function generateMenuOptions($items, $form, $model){

        foreach ($items as $item){
            echo '<div class="row"><div class="col-xs-3">'. $form->field($model, 'label[]')->textInput(['maxlength' => 1024, 'value' => $item['label']]). '</div>';
            echo '<div class="col-xs-8">'. $form->field($model, 'url[]')->textInput(['maxlength' => 1024, 'value' => $item['url']]) . '</div><div class="col-xs-1"><button type="button" class="btn btn-danger">X</button></div></div>';
        }

    }
}
echo generateMenuOptions($items, $form, $model);
?>