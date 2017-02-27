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


use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

class MenuHelper
{


    public static function getForm($items, $form, $model)
    {
        $depth = 0;

        return self::generateForm($items, $form, $model, $depth);
    }

    /**
     * @param $items
     * @param $form ActiveForm
     * @param $model
     * @param $depth
     * @param string $ttt
     * @return string
     */
    public static function generateForm($items, $form, $model, $depth, $ttt = '[items]')
    {
        $html = '';
        $index = 0;
        if (empty($items)) {
            $items = [['icon' => '', 'label' => '', 'url' => ['/controller/action']]];
        }
        foreach ($items as $key => $item) {
            $id = (int)microtime(true) + $index + $depth;

            $subItems = "";

            $suffix = $ttt . '[' . $key . ']';
            if (!empty($item['items'])) {
                $subItems = '<div class="col-xs-12 collapse" id="row-' . $id . '">' . self::generateForm($item['items'], $form, $model, $depth + 1, $suffix . '[items]') . '</div>';
            }

            $icon = $form->field($model, $suffix . 'icon', ['template' => '{label}<div class="om-icon-picker-preview"><span class="icon-preview"><i class="fa ' . ArrayHelper::getValue($item, 'icon') . '"></i></span></div><div class="table-cell">{input}</div>{hint}{error}'])->textInput(['maxlength' => 1024, 'value' => ArrayHelper::getValue($item, 'icon'), 'class' => 'icon-picker form-control']);
            $label = $form->field($model, $suffix . 'label')->textInput(['maxlength' => 1024, 'value' => ArrayHelper::getValue($item, 'label')]);
            $url = $form->field($model, $suffix . 'url')->textInput(['maxlength' => 1024, 'value' => \yii\helpers\Url::to($item['url'])]);

            $html .= '<div class="row">
                         <div class="col-xs-12">
                            <div class="row">
                                <div class="col-xs-2">' . $icon . '</div>
                                <div class="col-xs-2"> ' . $label . '</div>
                                <div class="col-xs-5">' . $url . '</div>
                                <div class="col-xs-3 om-menu-widget-actions-wrapper">
                                    <button type="button" class="btn btn-success">X</button>
                                    <button type="button" class="btn btn-danger">X</button>
                                    ' . (isset($item['items']) ? '<button type="button" data-toggle="collapse" class="btn btn-danger" href="#row-' . $id . '">^</button>' : '') . '
                                </div>
                            </div> 
                            ' . $subItems . '
                         </div>
                         
                </div>';
            $index++;
        }

        return $html;
    }


    /**
     * @param $model ActiveRecord
     * @param $form ActiveForm
     * @param array $properties
     * @param $baseOptions
     * @return string
     */
    public static function generateMenuBaseOptionInputs(ActiveRecord $model, $form, array $properties, $baseOptions)
    {
        $html = '';
        foreach ($properties as $property) {
            $value = ArrayHelper::getValue($baseOptions, $property['name'], $property['value']);
            if ($property['type'] == 'array') {
                $html .= '<h5><a class="btn-link" data-toggle="collapse" href="#" data-target="#' . $property['name'] . '-collapse" aria-expanded="false" aria-controls="' . $property['name'] . '-collapse">
                                ' . $model->getAttributeLabel($property['name']) . ' ^
                              </a>
                          </h5>';
                $html .= '<div class="collapse" id="' . $property['name'] . '-collapse"><div class="well"><p></p><div class="row">';

                foreach ($value as $i => $item) {
                    $html .= '<div class="col-xs-6">' . $form->field($model, 'base_options['.$property['name'].'][' . $i . '][property]')->textInput(['maxlength' => 1024, 'value' => $item['property']])->label($model->getAttributeLabel('property')) . '</div>';
                    $html .= '<div class="col-xs-6">' . $form->field($model, 'base_options['.$property['name'].'][' . $i . '][value]')->textInput(['maxlength' => 1024, 'value' => $item['value']])->label($model->getAttributeLabel('value')) . '</div>';
                }

                $html .= '</div></div></div>';
            }elseif($property['type'] == 'string'){
                $html .= $form->field($model, 'base_options['.$property['name'].']')->textInput(['maxlength' => 1024, 'value' => $value])->label($model->getAttributeLabel($property['name']));
            }elseif ($property['type'] == 'bool'){
                $html .= '<div class="form-group field-widgetmenu-base_options-activateitems">
                            <div class="checkbox">
                            <label for="widgetmenu-base_options-'.strtolower($property['name']).'">
                                <input type="hidden" name="WidgetMenu[base_options]['.$property['name'].']" value="0">
                                <input '.((bool)$value ? 'checked' : '').' type="checkbox" id="widgetmenu-base_options-'.strtolower($property['name']).'" name="WidgetMenu[base_options]['.$property['name'].']" value="1">'
                                    .$model->getAttributeLabel($property['name']) .
                            '</label>
                            <p class="help-block help-block-error"></p>
                            </div>
                         </div>';
            }
        }
        return $html;
    }


    public static function decodeOptions(&$options)
    {
        $dummyOptions = ['class' => '', 'style' => ''];

        if (!isset($options['itemOptions'])) {
            $options['itemOptions'] = $dummyOptions;
        }

        if (!isset($options['options'])) {
            $options['options'] = $dummyOptions;
        }

        if (!isset($options['params'])) {
            $options['params'] = $dummyOptions;
        }


        $decoded = [];
        foreach ($options['itemOptions'] as $key => $val) {
            $decoded[] = ['property' => $key, 'value' => $val];
        }

        $options['itemOptions'] = $decoded;

        $decoded = [];
        foreach ($options['options'] as $key => $val) {
            $decoded[] = ['property' => $key, 'value' => $val];
        }

        $options['options'] = $decoded;

        $decoded = [];
        foreach ($options['params'] as $key => $val) {
            $decoded[] = ['property' => $key, 'value' => $val];
        }

        $options['params'] = $decoded;
    }


    public static function encodeOptions(&$options)
    {
        $encoded = [];
        foreach ($options['itemOptions'] as $item) {
            $encoded[$item['property']] = $item['value'];
        }

        $options['itemOptions'] = $encoded;

        $encoded = [];
        foreach ($options['options'] as $item) {
            $encoded[$item['property']] = $item['value'];
        }

        $options['options'] = $encoded;

        $encoded = [];
        foreach ($options['params'] as $item) {
            $encoded[$item['property']] = $item['value'];
        }

        $options['params'] = $encoded;
    }


}