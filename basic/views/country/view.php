<?php

use app\models\Country;
use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;use yii\widgets\DetailView;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $model app\models\Country */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Countries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="country-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php if (Yii::$app->session->hasFlash('adminButtons')): ?>
    <p>
         <?= Html::a('Update', ['update', 'id' => $model->idFlag], ['class' => 'btn btn-primary']) ?>
         <?=Html::a('Delete', ['delete', 'id' => $model->idFlag], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ])?>
    </p>
    <?php endif; ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'code',
            'name',
            [
                'attribute' => 'flag',
                'value' => function ($model){
                    return $model->imageurl;
                },
                'format' => 'html',
            ],
            'languages:ntext',
        ],
    ]) ?>

</div>

<?php if (Yii::$app->session->hasFlash('usersCheckboxList')): ?>

    <?= HTML::a('Aktywuj kraj', ['act', 'idFlag' => $model->idFlag], ['class' => 'btn btn-success']) ?>

    <div class="country-form">
        <h1>Zaznacz osoby, które odwiedziły kraj <?= Html::encode($this->title) ?> </h1>

        <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($newVisits, 'idFlag')->hiddenInput(['value'=>$model->idFlag])->label(false) ?>
            <?= $form->field($newVisits, 'id')->label(false)->checkboxList($model->showUsersList()) ?>

        <div class="form-group">
            <?= Html::submitButton('Aktywuj kraj wraz z zaznaczonymi ', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
<?php else: ?>

<div class="visitor-view">
    <h1>Kraj <?= Html::encode($this->title) ?> został odwiedzony przez: </h1>
    <ul>
            <?php $users = $model->user; ?>
            <?php foreach ($users as $user):?>
                <li>
                    <?= $user->username?>
                    <?= $user->surname?>
                    <?= $user->name?>
                    <?= $user->age?>
                   <?= Html::a(Html::label("Pokaż profil"), ['user/view', 'id' => $user->id]);?>
                </li>
            <?php endforeach; ?>

    </ul>

</div>
<?php endif; ?>