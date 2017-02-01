<?php

namespace centigen\i18ncontent\controllers;

use centigen\base\helpers\LocaleHelper;
use centigen\i18ncontent\models\Article;
use centigen\i18ncontent\models\search\ArticleSearch;
use centigen\i18ncontent\models\ArticleCategory;
use centigen\i18ncontent\web\Controller;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ArticleController implements the CRUD actions for Article model.
 */
class ArticleController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'ToggleStatus' => ['post']
                ]
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
            ],
            'toggle-status' => [
                'class' => 'centigen\i18ncontent\actions\ToggleStatusAction',
                'model' => 'centigen\i18ncontent\models\Article',
            ]
        ];
    }

    /**
     * Lists all Article models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ArticleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'articleCategories' => ArticleCategory::getCategories()
        ]);
    }

    /**
     * Creates a new Article model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Article();
        $articleCategories = ArticleCategory::getCategories(true);
        $locales = LocaleHelper::getAvailableLocales();

        if ($model->load(Yii::$app->request->post(), null) && $model->save()) {
            return $this->redirect(['index']);
        }
        return $this->render('create', [
            'model' => $model,
            'locales' => $locales,
            'categories' => $articleCategories
        ]);
    }

    /**
     * Updates an existing Article model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->category_ids = ArrayHelper::getColumn($model->articleCategoryArticles, 'category_id');

        $articleCategories = ArticleCategory::getCategories(true);
        $locales = LocaleHelper::getAvailableLocales();

        if ($model->load(Yii::$app->request->post(), null) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
            'categories' => $articleCategories,
            'locales' => $locales
        ]);

    }

    /**
     * Deletes an existing Article model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer|array $id
     * @return mixed
     */
    public function actionDelete($id = null)
    {
        $id = $id ?: Yii::$app->request->post('id');
        Article::deleteAll(['id' => $id]);

        return $this->redirect(['index']);
    }


    /**
     * Finds the Article model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Article the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Article::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
