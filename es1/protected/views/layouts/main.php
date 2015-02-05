<?php
/* @var $this CController */

$bootstrap_dir = Yii::app()->baseUrl .'/bootstrap/css/';

Yii::app()->clientScript->registerCssFile(
    $bootstrap_dir .'bootstrap.css'
);

Yii::app()->clientScript->registerCssFile(
    $bootstrap_dir .'bootstrap-theme.css'
);

Yii::app()->clientScript->registerCssFile(
    Yii::app()->baseUrl ."/css/main.layout.css"
);

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?=CHtml::encode($this->pageTitle)?></title>
    </head>
    <body>
        <div class="header">
            <a href="/es2"><img src='<?=Yii::app()->baseUrl."/img/logo.png"?>' /></a>
        </div>
        <div class="main">
            <?=$content?>
        </div>
    </body>
</html>