<?php

namespace centigen\i18ncontent\controllers;

use centigen\i18ncontent\models\search\WidgetCarouselItemSearch;
use centigen\i18ncontent\models\WidgetCarousel;
use centigen\i18ncontent\models\search\WidgetCarouselSearch;
use centigen\i18ncontent\models\WidgetCarouselItemTranslation;
use centigen\i18ncontent\web\Controller;
use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * WidgetCarouselController implements the CRUD actions for WidgetCarousel model.
 */
class WidgetCarouselController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post']
                ],
            ],
            \centigen\base\behaviors\LayoutBehavior::className()
        ];
    }

    public function actions()
    {
        return [
            'toggle-status' => [
                'class' => 'centigen\i18ncontent\actions\ToggleStatusAction',
                'model' => 'centigen\i18ncontent\models\WidgetCarousel',
            ]
        ];
    }

    /**
     * Lists all WidgetCarousel models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WidgetCarouselSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Creates a new WidgetCarousel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new WidgetCarousel();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing WidgetCarousel model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $searchModel = new WidgetCarouselItemSearch();
        $carouselItemsProvider = $searchModel->search([]);
        $carouselItemsProvider->query->andWhere(['carousel_id' => $model->id]);
        $carouselItemsProvider->sort->attributes['activeTranslation.caption'] = [
            'asc' => [WidgetCarouselItemTranslation::tableName().'.caption' => SORT_ASC],
            'desc' => [WidgetCarouselItemTranslation::tableName().'.caption' => SORT_DESC],
        ];

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
                'carouselItemsProvider' => $carouselItemsProvider
            ]);
        }
    }

    /**
     * Deletes an existing WidgetCarousel model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the WidgetCarousel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WidgetCarousel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WidgetCarousel::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
