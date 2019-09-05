<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

use app\models\Country;

$this->title = 'Lista krajów';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
    <ul>
        <?php foreach ($countries as $country):?>
            <li>
                <?= $country->imageurl ?>
                <?= Html::encode("{$country->name} ({$country->code})") ?>:
                <?='Odwiedziło '.Country::countVisitors($country).' użytkowników' ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <?= LinkPager::widget(['pagination' => $pagination]) ?>

    <a href="<?= Url::toRoute(['site/excel'])?>" class="btn btn-success">Eksportuj do excela</a>

</div>
