<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Pokaż kraje', ['country/'], ['class' => 'btn btn-primary']) ?>
    </p>
    <p>
        <?= Html::a('Mój profil', ["/user/view", 'id' => Yii::$app->user->getId()], ['class' => 'btn btn-primary']) ?>
    </p>

<!--    --><?php //echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'username',
            'name',
            'surname',
            'age',
            'email',

            ['class' => 'yii\grid\ActionColumn', 'template' => '{view}'],
        ],
    ]) ?>


</div>
