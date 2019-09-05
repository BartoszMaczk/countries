<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Country */

$this->title = 'Create Country';
$this->params['breadcrumbs'][] = ['label' => 'Countries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="country-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->session->hasFlash('countryAdded')): ?>

    <div class="alert alert-success">
        Dziękujemy za zgłoszenie nowego kraju. Po zatwierdzeniu przez administratora, zostanie dodany do naszej listy.
    </div>

    <?php else: ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

    <?php endif; ?>
</div>
