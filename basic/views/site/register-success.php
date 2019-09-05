<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Aktywacja konta';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->session->hasFlash('usernameError')): ?>

        <code>
            Błąd bazy danych.
            <p>Przepraszamy, nie znaleziono Cię w bazie danych. Spróbuj zarejestrować się ponownie lub skontaktuj się z administratorem</p>
        </code>

    <?php elseif (Yii::$app->session->hasFlash('activKeyError')): ?>

        <code>
            Błąd bazy danych.
            <p>Przepraszamy, Twój klucz aktywacyjny jest niepoprawny lub wygasł. Zarejestruj się ponownie</p>
        </code>

    <?php else: ?>

        <div class="alert alert-success">
            Sukces! Udało Ci się zarejestrować w naszej aplikacji! Witamy na pokładzie :)
        </div>
        <p>Możesz już się zalogować na swoje konto</p>
        <a href="<?= Url::toRoute(['site/login'])?>" class="btn btn-success">Przejdź do logowania</a>

    <?php endif; ?>
</div>
