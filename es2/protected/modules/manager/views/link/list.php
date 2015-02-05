<?php
/* @var $this CrudController */
/* @var $model CActiveDataProvider */

$this->pageTitle = "Список связей между гипотезами";

$this->widget("zii.widgets.grid.CGridView", array(
    "dataProvider" => $model,
    "columns" => array(
        "id",
        array(
            "value" => '$data->parent->name',
            "name" => "Родитель"
        ),
        array(
            "value" => '$data->listChildsHypsToString()',
            "name" => "Потомки"
        ),
        array(
            "value" => '$data->type->name',
            "name" => "Тип"
        ),
        "LS",
        "LN",
        array(
            "class" => "CButtonColumn",
            "header" => "Действия",
            "template" => "{update} {delete}"
        ),
    ),
));

?>