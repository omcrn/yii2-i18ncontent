<?php

namespace centigen\i18ncontent\controllers;

use centigen\base\helpers\LocaleHelper;
use centigen\i18ncontent\models\ArticleCategory;
use centigen\i18ncontent\models\search\ArticleCategorySearch;
use centigen\i18ncontent\web\Controller;
use Yii;
use yii\helpers\Json;
use yii\helpers\Url;
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

    public function actions()
    {
        return [
            'toggle-status' => [
                'class' => 'centigen\i18ncontent\actions\ToggleStatusAction',
                'model' => 'centigen\i18ncontent\models\ArticleCategory',
            ]
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
            'categories' => ArticleCategory::getCategories()
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
        $model = new ArticleCategory();
        $articleCategories = ArticleCategory::getCategories();
        $locales = LocaleHelper::getAvailableLocales();

        if ($model->load(Yii::$app->request->post(), null) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
            'categories' => $articleCategories,
            'locales' => $locales
        ]);

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

        $articleCategories = ArticleCategory::getCategories();
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
     * Deletes an existing ArticleCategory model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer|null $id
     * @return mixed
     */
    public function actionDelete($id = null)
    {
        $id = $id ?: Yii::$app->request->post('id');
        if (is_array($id)) {
            $categoryWithChildren = [];
            foreach ($id as $item) {
                $model = $this->findModel($item);
                if (count($model->articleCategoryArticles) > 0) {
                    $categoryWithChildren[] = $model->activeTranslation->title;
                    continue;
                }
                $model->delete();
            }
            if(count($categoryWithChildren)){
                $msg = Yii::t('i18ncontent', 'This categories has articles. In order to delete this categories, first you must delete all articles in this categories').': '. join(', ', $categoryWithChildren);
                return Json::encode(['errorMsg' => $msg, 'success' => false]);
            }
            return $this->redirect(['index']);
        } else {
            $model = $this->findModel($id);
            if (count($model->articleCategoryArticles) > 0) {
                $msg = Yii::t('i18ncontent', 'This category has articles. In order to delete this category, first you must delete all articles in this category {link}', ['link' => '<a href="' . Url::to(['article/index', 'ArticleSearch' => ['category_ids' => $model->id]]) . '">' . Yii::t('i18ncontent', 'View them') . '</a>']);
                return $this->renderContent($msg);
            }
            $model->delete();
        }

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

}
