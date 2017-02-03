<?php
/**
 * User: zura
 * Date: 10/27/2016
 * Time: 10:32 AM
 */

namespace centigen\i18ncontent\controllers;
use centigen\i18ncontent\models\search\WidgetMenuSearch;
use centigen\i18ncontent\models\WidgetMenu;
use Yii;
use yii\filters\VerbFilter;
use centigen\i18ncontent\web\Controller;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;


/**
 * Class WidgetMenuController
 *
 * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
 * @package centigen\i18ncontent\controllers
 */
class WidgetMenuController extends Controller
{
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
            'toggle-status' => [
                'class' => 'centigen\i18ncontent\actions\ToggleStatusAction',
                'model' => 'centigen\i18ncontent\models\WidgetMenu',
            ]
        ];
    }

    /**
     * Lists all WidgetMenu models.
     * @return mixed
     */
    public function actionIndex()
    {

        $searchModel = new WidgetMenuSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new WidgetMenu model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new WidgetMenu();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing WidgetMenu model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if(Yii::$app->request->isPost) {
            $postData = Yii::$app->request->post();
            $model->load($postData);
            $model->items = Json::encode($model->items);
            if ($model->save()) {
                return $this->redirect(['index']);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }

    }

    /**
     * Deletes an existing WidgetMenu model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer|null $id
     * @return mixed
     */
    public function actionDelete($id = null)
    {
        $id = $id ?: Yii::$app->request->post('id');
        WidgetMenu::deleteAll(['id' => $id]);

        return $this->redirect(['index']);
    }

    /**
     * Finds the WidgetMenu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WidgetMenu the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WidgetMenu::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}