<?php

namespace app\controllers;

use Yii;
use yii\console\widgets\Table;
use yii\db\Exception;
use app\rbac\AuthorRule;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\rbac\DbManager;
use yii\rbac\PhpManager;
use yii\web\Controller;
use yii\web\Response;
use yii\data\Pagination;
use app\models\LoginForm;
use app\models\Country;
use app\models\RegisterForm;
use app\models\User;
use app\models\Visited;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->render('index');
        }else {
            $this->redirect(array("/country/index"));
        };
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays register page.
     *
     * @return Response|string
     */
    public function actionRegister()
    {
        $model = new RegisterForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            $newUser = new User();
            $newUser->username = $model->username;
            $newUser->password = Yii::$app->getSecurity()->generatePasswordHash($model->password);
            $newUser->name = $model->name;
            $newUser->surname = $model->surname;
            $newUser->email = $model->email;
            $newUser->age = $model->age;
            $newUser->activKey = $model->activation_key;
            $newUser->activationstatus = 0;
            $newUser->save();

            $auth = Yii::$app->authManager;
            $authorRole = $auth->getRole('author');
            $auth->assign($authorRole, $newUser->getId());

            Yii::$app->session->setFlash('registerFormSubmitted');
            return $this->refresh();
        }
        return $this->render('register', [
            'model' => $model,
        ]);
    }
    public function actionActivate(){
        if(!isset($_GET['key']) || !isset($_GET['id'])){
            $this->redirect(array("/site/register"));
        } else{
            $query = User::find()->where('username ="'.$_GET['id'].'"')->all();
            if(count($query) == 0 || count($query) > 1){
                Yii::$app->session->setFlash('usernameError');
            } else {
                if($query[0]['activKey'] ==$_GET['key']){
                    $query[0]->activationstatus = true;
                    $query[0]->save();
                } else{
                    Yii::$app->session->setFlash('activKeyError');
                }
            }
            return $this->render('register-success');
        }
    }

    public function actionAbout()
    {
            $query = Country::find()->where('toCheck = 1');

            $pagination = new Pagination([
                'defaultPageSize' => 5,
                'totalCount' => $query->count(),
            ]);

            $countries = $query->orderBy('name')
                ->offset($pagination->offset)
                ->limit($pagination->limit)
                ->all();

            return $this->render('about', [
                'countries' => $countries,
                'pagination' => $pagination,
            ]);
    }

    public function actionExcel(){
            $file = Yii::createObject([
                'class' => 'codemix\excelexport\ExcelFile',
                'sheets' => [
                    'Countries' => [
                        'class' => 'codemix\excelexport\ActiveExcelSheet',
                        'query' => Country::find()->select(array('code','name', 'languages'))->where('toCheck = 1'),
                        'titles' => [
                            'Kod',
                            'Nazwa',
                            'Języki urzędowe',
                            'Odwiedzone przez:',
                            '',
                            '',
                        ],
                        'attributes' => [
                            'code',
                            'name',
                            'languages',
                        ],
                        'styles' => [
                            'A1:Z1' => [
                                'borders' => [
                                  'bottom' => [
                                      'style' => \PHPExcel_Style_Border::BORDER_THIN,
                                  ],
                                ],
                                'font' => [
                                    'bold' => true,
                                    'color' => ['rgb' => '449D44'],
                                    'size' => 10,
                                    'name' => 'Verdana'
                                    ],
                                ],
                        ],
                        'on afterRender' => function ($event) {
                            $sheet = $event->sender->getSheet();
                            $i = 2;
                            $countrycodes = Country::find()->select(array('idFlag'))->where('toCheck = 1')->all();
                            foreach ($countrycodes as $oneCode) {
                                $visits = count(Visited::find()->where('idFlag="' . $oneCode["idFlag"] . '"')->all());
                                $sheet->setCellValue("D$i", "$visits uzytkownikow");
                                $i++;
                            }
                        }
                    ]
                ]
            ]);
            $file->send('lista.xlsx');
    }

}
