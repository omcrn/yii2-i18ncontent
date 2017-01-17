<?php

/**
 * Created by PhpStorm.
 * User: koco
 * Date: 1/16/2017
 * Time: 1:30 PM
 */

namespace centigen\i18ncontent\actions;

use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\web\NotFoundHttpException;
use yii\web\Response;


/**
 * Class ToggleStatusAction
 *
 * @author Giorgi Keshikashvili
 * @package centigen\i18ncontent\actions
 */
class ToggleStatusAction extends Action
{
    /**
     * model class name with namespace
     *
     * @var null
     */
    public $model = null;

    /**
     * status field name
     *
     * @var string
     */
    public $statusField = 'status';


    public function init()
    {
        if($this->model === null){
            throw new InvalidConfigException('In class centigen\i18ncontent\actions\ToggleStatusAction model param must be passed!');
        }
        parent::init();
    }

    public function run()
    {
        $request = Yii::$app->request;
        $model = $this->findModel($request->post('id'));
        $model->{$this->statusField} = (int)!$model->{$this->statusField};

        if($model->save()){
            $res = [
                'success' => true,
                'msg' => '',
                'errors' => '',
            ];
        }else{
            $res = [
                'success' => false,
                'msg' => Yii::t('i18ncontent', 'Status change error!'),
                'errors' => $model->errors,
            ];
        }
        Yii::$app->response->format = Response::FORMAT_JSON;

        return $res;
    }


    protected function findModel($id)
    {
        $model = $this->model;
        if (($model = $model::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}