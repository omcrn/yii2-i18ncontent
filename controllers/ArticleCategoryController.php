<?php

namespace centigen\i18ncontent\controllers;

use centigen\i18ncontent\helpers\BaseHelper;
use centigen\i18ncontent\models\ArticleCategory;
use centigen\i18ncontent\models\search\ArticleCategorySearch;
use centigen\i18ncontent\web\Controller;
use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ArticleCategoryController implements the CRUD actions for ArticleCategory model.
 */
class ArticleCategoryController extends Controller
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

    /**
     * Lists all ArticleCategory models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ArticleCategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ArticleCategory model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ArticleCategory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $locales = BaseHelper::getAvailableLocales();
        if ($request->post()) {

            $model = $request->post('ArticleCategory');
            $translations = $request->post('ArticleCategoryTranslations');

//            \ChromePhp::log($model, $translations);
            if (ArticleCategory::processAndSave($model, $translations, $locales)) {
                return $this->redirect(['index']);
            } else {
                return $this->renderCreateForm();
            }

        } else {
            return $this->renderCreateForm();
        }
    }

    /**
     * Updates an existing ArticleCategory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $translations = $model->translations;

        $request = Yii::$app->request;
        $locales = BaseHelper::getAvailableLocales();
        if ($request->post()) {

            $modelData = $request->post('ArticleCategory');
            $languages = $request->post('ArticleCategoryTranslations');

//            \ChromePhp::log($model);
            if (ArticleCategory::processAndUpdate($model, $translations, $modelData, $languages, $locales)) {
                return $this->redirect(['index']);
            } else {
                return $this->renderUpdateForm($model, $translations);
            }

        } else {
            return $this->renderUpdateForm($model, $translations);
        }

    }

    /**
     * Deletes an existing ArticleCategory model.
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
     * Finds the ArticleCategory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ArticleCategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ArticleCategory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function renderCreateForm()
    {
        return $this->render('create', [
            'model' => new ArticleCategory(),
            'categories' => ArticleCategory::getCategories(),
            'locales' => BaseHelper::getAvailableLocales()
        ]);
    }

    protected function renderUpdateForm($model, $translations)
    {
        return $this->render('update', [
            'model' => $model,
            'categories' => ArticleCategory::getCategories(),
            'translations' => $translations,
            'locales' => BaseHelper::getAvailableLocales()
        ]);
    }
}
