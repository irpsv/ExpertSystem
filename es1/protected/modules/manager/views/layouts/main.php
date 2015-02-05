<?php
/* @var $this CController */
Yii::app()->clientScript->registerCoreScript('jquery');
Yii::import('application.controllers.ScriptController');
ScriptController::importBootstrap();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    </head>
    <body>
        <div class='menu'>
            <?=CHtml::link("Список вопросов", $this->createAbsoluteUrl("quest/list"))?> / 
            <?=CHtml::link("Создать вопрос", $this->createAbsoluteUrl("quest/create"))?>
        </div>
        <div class='content'>
            <?php echo $content; ?>
        </div>
    </body>
</html>
