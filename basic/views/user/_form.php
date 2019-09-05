<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textarea(['rows' => 2]) ?>

    <?= $form->field($model, 'name')->textarea(['rows' => 2]) ?>

    <?= $form->field($model, 'surname')->textarea(['rows' => 2]) ?>

    <?= $form->field($model, 'email')->textarea(['rows' => 2]) ?>

    <?= $form->field($model, 'age')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
