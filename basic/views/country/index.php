<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CountrySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Kraje';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="country-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Pokaż użytkowników', ['user/'], ['class' => 'btn btn-primary']) ?>
    </p>
    <p>
        <?= Html::a('Mój profil', ["/user/view", 'id' => Yii::$app->user->getId()], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Zgłoś nowy kraj', ['create'], ['class' => 'btn btn-success']) ?>

        <?php if (Yii::$app->session->hasFlash('checkButton')): ?>
        <?= Html::a('Panel Admina / Sprawdź zgłoszone', ['admin/index'], ['class' => 'btn btn-danger']) ?>
        <?php endif; ?>
        <?php if (Yii::$app->session->hasFlash('checkFuncs')): ?>
        <?= Html::a('Powrót do listy', ['country/index'], ['class' => 'btn btn-danger']) ?>
        <?php endif; ?>
    </p>


    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'code',
            'name',
            array(
                    'format' => 'html',
                    'value' => function ($data){
                        return $data->imageurl;
                    }
            ),
            'languages:ntext',

            ['class' => 'yii\grid\ActionColumn', 'template' => '{view}'],
            [
                    'class' => 'yii\grid\ActionColumn',
                'template' => '{dodaj}',
                'buttons' => [
                        'dodaj' => function ($url, $model, $key) {
                            if (Yii::$app->session->hasFlash('checkFuncs')) {
                                return Html::a('Przjedź do aktywacji', ['view', 'id' => $model->idFlag], ['class' => 'btn btn-success']);
                            }
                            if(!$model->isVisited($model->idFlag)){
                                $id = Yii::$app->user->getId();
                                return HTML::a('Dodaj do odwiedzonych', ['add', 'id' => $model->idFlag]);
                            }
                        }
                ]
                ],
        ],
    ]); ?>


</div>
