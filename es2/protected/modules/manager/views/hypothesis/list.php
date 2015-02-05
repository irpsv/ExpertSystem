<?php
/* @var $this CrudController */
/* @var $model CActiveDataProvider */

$this->pageTitle = "Список гипотез";

$this->widget("zii.widgets.grid.CGridView", array(
    "dataProvider" => $model,
    "columns" => array(
        "id",
        "name",
        "odds",
        "c_value",
        array(
            "class" => "CButtonColumn",
            "header" => "Действия",
        ),
    ),
));

?>