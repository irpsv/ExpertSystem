<?php
/* @var $this CrudController */
/* @var $model CActiveDataProvider */

$this->pageTitle = "Список связей тезауруса";

$this->widget("zii.widgets.grid.CGridView", array(
    "dataProvider" => $model,
    "columns" => array(
        "id",
        "name",
        array(
            "name" => "Аргументы",
            "value" => '$data->getArgumentsToString()',
        ),
        array(
            "class" => "CButtonColumn",
            "header" => "Действия",
            "template" => "{update} {delete}"
        ),
    ),
));

?>