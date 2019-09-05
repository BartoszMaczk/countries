<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>

<h1>Dzien dobry <?= $name ?></h1>
<p>Dostales tego maila, poniewaz wypelniles formularz rejestracyjny w naszej aplikacji "Lista krajow"
<br/>Dla pomyslnego ukonczenia procesu rejestracji aktywuj swoje konto poprzez link aktywacyjny: </p>
<p><a href="<?=$link?>"><?= $link ?></a></p>
