<?php
/* @var $this CrudController */
/* @var $model CActiveDataProvider */

$this->pageTitle = "Список связей между гипотезами и понятиями";

$this->widget("zii.widgets.grid.CGridView", array(
    "dataProvider" => $model,
    "columns" => array(
        array(
            "value" => '$data->hypothesis->name',
            "name" => "Гипотеза"
        ),
        array(
            "value" => '$data->getListSymbols()',
            "name" => "Список понятий"
        ),
        array(
            "class" => "CButtonColumn",
            "header" => "Удалить",
            "template" => "{delete}"
        ),
    ),
));

?>