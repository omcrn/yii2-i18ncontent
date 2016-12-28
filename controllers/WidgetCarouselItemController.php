<?php

namespace centigen\i18ncontent\controllers;

use centigen\i18ncontent\helpers\BaseHelper;
use centigen\i18ncontent\models\WidgetCarousel;
use centigen\i18ncontent\models\WidgetCarouselItem;
use centigen\i18ncontent\web\Controller;
use Yii;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * WidgetCarouselItemController implements the CRUD actions for WidgetCarouselItem model.
 */
class WidgetCarouselItemController extends Controller
{

    public function getViewPath()
    {
        return $this->module->getViewPath() . DIRECTORY_SEPARATOR . 'widget-carousel/item';
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            \centigen\base\behaviors\LayoutBehavior::className()
        ];
    }

    public function actions()
    {
        return [
            'upload' => [
                'class' => 'trntv\filekit\actions\UploadAction',
                'deleteRoute' => 'upload-delete'
            ],
            'upload-delete' => [
                'class' => 'trntv\filekit\actions\DeleteAction'
            ],
            'upload-imperavi' => [
                'class' => 'trntv\filekit\actions\UploadAction',
                'fileparam' => 'file',
                'responseUrlParam'=> 'filelink',
                'multiple' => false,
                'disableCsrf' => true
            ]
        ];
    }

    /**
     * Creates a new WidgetCarouselItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param $carousel_id
     * @throws HttpException
     * @return mixed
     */
    public function actionCreate($carousel_id)
    {
        /**
         * @var $carousel WidgetCarousel
         */
        $carousel = WidgetCarousel::findOne($carousel_id);
        if (!$carousel) {
            throw new HttpException(400);
        }

        $model = new WidgetCarouselItem();
        $locales = BaseHelper::getAvailableLocales();

        if ($model->load(Yii::$app->request->post(), null) && $model->save()) {
            return $this->redirect(['widget-carousel/update', 'id' => $carousel_id]);
        }
        return $this->render('create', [
            'model' => $model,
            'locales' => $locales,
            'carousel' => $carousel
        ]);

    }

    /**
     * Updates an existing WidgetCarouselItem model.
     * If update is successful, the browser will be redirected to the carousel update page
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $carousel = $model->carousel;

        $locales = BaseHelper::getAvailableLocales();

        if ($model->load(Yii::$app->request->post(), null) && $model->save()) {
            return $this->redirect(['widget-carousel/update', 'id' => $model->carousel_id]);
        }

        return $this->render('update', [
            'model' => $model,
            'locales' => $locales,
            'carousel' => $carousel
        ]);
    }

    /**
     * Deletes an existing WidgetCarouselItem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->delete()) {
            return $this->redirect(['widget-carousel/update', 'id' => $model->carousel_id]);
        };
        return "";
    }

    /**
     * Finds the WidgetCarouselItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WidgetCarouselItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WidgetCarouselItem::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
