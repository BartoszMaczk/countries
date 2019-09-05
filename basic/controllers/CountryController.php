<?php

namespace app\controllers;

use app\models\User;
use app\models\Visited;
use phpDocumentor\Reflection\Types\Null_;
use Yii;
use app\models\Country;
use app\models\CountrySearch;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CountryController implements the CRUD actions for Country model.
 */
class CountryController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'view', 'create', 'update', 'delete', 'add', 'check', 'activateCountry'],
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'update', 'delete', 'create', 'add', 'check', 'activateCountry'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['index', 'view', 'update', 'delete', 'create', 'add', 'check', 'activateCountry'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Country models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(Yii::$app->authManager->getAssignment('admin', Yii::$app->user->getId())){
            Yii::$app->session->setFlash('checkButton');
        }
        $searchModel = new CountrySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Country model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if(Yii::$app->authManager->getAssignment('admin', Yii::$app->user->getId())){
            Yii::$app->session->setFlash('adminButtons');
        }
        $model = $this->findVisitors($id);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Country model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Country();
        $model -> toCheck = 0;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('countryAdded');
            return $this->refresh();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
    /**
     * Creates a new Visited model.
     * If creation is successful, the browser will be redirected to the 'user/view' page.
     * @return mixed
     */
    public function actionAdd()
    {
        if(!isset($_GET['idFlag'])){
            return;
        }
        if(Country::isVisited($_GET['idFlag'])){
            return "Ten kraj został juz przypisany do Twojego konta!";
        }
        $model = new Visited();
        $model -> id = Yii::$app->user->getId();
        $model -> idFlag = $_GET['idFlag'];
        $model ->save();
        $this->redirect(["/user/view", 'id' => $model->id]);
    }

    /**
     * Updates an existing Country model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if(Yii::$app->authManager->getAssignment('admin', Yii::$app->user->getId())) {
            $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->idFlag]);
            }

            return $this->render('update', [
                'model' => $model,
            ]);
        }
        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * Deletes an existing Country model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if (Yii::$app->authManager->getAssignment('admin', Yii::$app->user->getId())) {
            $this->findModel($id)->delete();

            return $this->redirect(['index']);
        }
        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * Finds the Country model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Country the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Country::findOne($id)) !== null) {
            if($model->toCheck === 1) {
                return $model;
            }
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findVisitors($id)
    {
        if (($list = Country::find()
                ->where("country.idFlag=$id")
                ->andWhere("country.toCheck=1")
                ->joinWith('user')->one()) !== null) {
            return $list;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
