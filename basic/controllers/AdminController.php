<?php

namespace app\controllers;

use app\models\Visited;
use Yii;
use app\models\Country;
use app\models\CountrySearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AdminController implements the CRUD actions for Country model.
 */
class AdminController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
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
        if(Yii::$app->authManager->getAssignment('admin', Yii::$app->user->getId())) {
            Yii::$app->session->setFlash('checkFuncs');
            $searchModel = new CountrySearch();
            $dataProvider = $searchModel->searchToCheck(Yii::$app->request->queryParams);

            return $this->render('/country/index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
        $this->redirect(["/country/index"]);
    }

    /**
     * Displays a single Country model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if(Yii::$app->authManager->getAssignment('admin', Yii::$app->user->getId())) {
            Yii::$app->session->setFlash('adminButtons');
            Yii::$app->session->setFlash('usersCheckboxList');

            $newVisits = new Visited();
            if ($newVisits->load(Yii::$app->request->post()) && $newVisits->save()) {
                $this->activateFlag($newVisits->idFlag);
                return $this->redirect(['/admin/index']);
            }

            return $this->render('/country/view', [
            'model' => $this->findModel($id),
                'newVisits' => $newVisits,
        ]);
        }
        $this->redirect(["/country/index"]);
    }


    public function actionAct($idFlag)
    {
        $this->activateFlag($idFlag);
        return $this->redirect(['/admin/index']);
    }

    /**
     * Creates a new Country model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Country();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->idFlag]);
        }

        return $this->render('/country/create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Country model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->idFlag]);
        }

        return $this->render('/country/update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Country model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Country model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Country the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Country::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function activateFlag($idFlag)
    {
        if(Yii::$app->authManager->getAssignment('admin', Yii::$app->user->getId())) {

            $model = Country::find()->where("idFlag=$idFlag")->one();
            $model->toCheck = 1;
            $model->save();
            return $this->redirect(["/admin/index"]);
        }
        return $this->redirect(["/country/index"]);
    }

}
