<?php
/**
 * Created by PhpStorm.
 * User: koco
 * Date: 2/2/2017
 * Time: 3:22 PM
 * @var $items array
 * @var $form yii\bootstrap\ActiveForm
 * @var $model \centigen\i18ncontent\models\WidgetMenu
 *
 */

namespace centigen\i18ncontent\helpers;


use yii\helpers\ArrayHelper;

class MenuHelper
{



    public  function getItems($items, $form, $model)
    {
        $depth = 0;

        return $this->generateItems($items, $form, $model, $depth);
    }

    public function generateItems($items, $form, $model, $depth, $ttt = '[items]')
    {
        $html = '';
        $index = 0;
        foreach ($items as $key => $item) {
            $id = (int)microtime(true) + $index + $depth;

            $subItems = "";

            $suffix = $ttt . '[' . $key . ']';
            if (!empty($item['items'])){
                $subItems = '<div class="col-xs-12 collapse" id="row-'.$id.'">'.$this->generateItems($item['items'], $form, $model, $depth+1, $suffix.'[items]').'</div>';
            }

            $html .= '<div class="row">
                         <div class="col-xs-12">
                            <div class="row">
                                <div class="col-xs-1"> ' . $form->field($model, $suffix.'icon')->textInput(['maxlength' => 1024, 'value' => ArrayHelper::getValue($item, 'icon')]) . '</div>
                                <div class="col-xs-2"> ' . $form->field($model, $suffix.'label')->textInput(['maxlength' => 1024, 'value' => ArrayHelper::getValue($item, 'label')]) . '</div>
                                <div class="col-xs-6">' . $form->field($model, $suffix.'url')->textInput(['maxlength' => 1024, 'value' => \yii\helpers\Url::to($item['url'])]) . '</div>
                                <div class="col-xs-3" style="padding-top:25px">
                                    <button type="button" class="btn btn-success">X</button>
                                    <button type="button" class="btn btn-danger">X</button>
                                    ' . (isset($item['items']) ? '<button type="button" data-toggle="collapse" class="btn btn-danger" href="#row-' . $id . '">^</button>' : '') . '
                                </div>
                            </div> 
                            ' . $subItems .'
                         </div>
                         
                </div>';
            $index++;
        }

        return $html;
    }

}