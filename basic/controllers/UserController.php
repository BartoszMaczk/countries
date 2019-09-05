<?php

namespace app\controllers;

use Yii;
use app\models\Country;
use app\models\Visited;
use app\models\User;
use app\models\UserSearch;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

use kartik\export\ExportMenu;
use kartik\grid\GridView;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'view', 'create', 'update', 'delete'],
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['index', 'view', 'update', 'delete', 'create'],
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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {   $user_id = Yii::$app->user->getId();
        if ((Yii::$app->authManager->getAssignment('admin', $user_id)) || ($user_id == $id)) {
            Yii::$app->session->setFlash('adminButtons');
        }
        $model = $this->findVisited($id);
        $dataProvider = new ActiveDataProvider([
            'query' => User::find()->where("users.id=$id")
                ->andWhere("users.activationstatus = 1")
                ->joinWith('country'),
        ]);
        return $this->render('view', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if ((Yii::$app->authManager->getAssignment('admin', Yii::$app->user->getId())) || (Yii::$app->user->can('updateOwnProfile', ['post' => $id]))) {
            $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }

            return $this->render('update', [
                'model' => $model,
            ]);
        }
        return $this->goBack();
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if ((Yii::$app->authManager->getAssignment('admin', Yii::$app->user->getId())) || (Yii::$app->user->can('deleteOwnProfile', ['post' => $id]))) {
            $this->findModel($id)->delete();

            return $this->redirect(["/site/logout"]);
        }
        return $this->goBack();
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            if($model->activationstatus === 1) {
                return $model;
            }
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
    protected function findVisited($id)
    {
        if (($list = User::find()
                ->where("users.id=$id")
                ->andWhere("users.activationstatus = 1")
                ->joinWith('country')
                ->one()) !== null) {
            return $list;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
