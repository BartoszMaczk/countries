<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use kartik\export\ExportMenu;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php if (Yii::$app->session->hasFlash('adminButtons')): ?>
    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?php endif; ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'username:ntext',
            'name:ntext',
            'surname:ntext',
            'email:ntext',
            'age',
            [
                'attribute' => 'Odwiedzone kraje',
                'value' => function ($model){
                    $countryFlag = $model->country;
                    $flags = '';
                    foreach ($countryFlag as $flag){
                        $flags .= Html::a(Html::label("$flag->imageurl"), ['country/view', 'id' => $flag->idFlag]);
                    }
                    return $flags;
                },
                'format' => 'html',
            ],
        ],
    ]) ?>


    <?php
    $gridColumns = [
    ['class' => 'kartik\grid\SerialColumn'],
    'username',
    'name',
    'surname',
    'email',
    'age',
        [
            'attribute' => 'Odwiedzone kraje',
            'value' => function ($model){
                $countryFlag = $model->country;
                $flags = '';
                foreach ($countryFlag as $flag){
                    $flags .= $flag->name;
                    $flags .= "  |  ";
                }
                return $flags;
            },
            'format' => 'raw',
        ],

    ['class' => 'kartik\grid\ActionColumn', 'urlCreator'=>function(){return '#';}]
    ];
    echo ExportMenu::widget([
    'dataProvider' => $dataProvider,
    'columns' => $gridColumns,
    ]);
    ?>
</div>
