<?php
/* @var $this CController */
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?=$this->pageTitle?></title>
    </head>
    <body>
        <div class="menu">
            <?php
            echo CHtml::link("Гипотезы и вопросы", $this->createAbsoluteUrl("hypothesis/list"));
            echo " | ";
            echo CHtml::link("Связи", $this->createAbsoluteUrl("link/list"));
            echo " | ";
            echo CHtml::link("Понятия", $this->createAbsoluteUrl("symbol/list"));
            echo " | ";
            echo CHtml::link("Предикаты", $this->createAbsoluteUrl("predicat/list"));
            echo " | ";
            echo CHtml::link("Связи понятий и гипотез", $this->createAbsoluteUrl("linkhs/list"));
            ?>
            <hr>
            <?php
            echo CHtml::link("Добавить запись", $this->createAbsoluteUrl("{$this->id}/create"));
            echo " | ";
            echo CHtml::link("Список записей", $this->createAbsoluteUrl("{$this->id}/list"));
            ?>
        </div>
        <div class="content">
            <?=$content?>
        </div>
    </body>
</html>
