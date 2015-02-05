<?php
/* @var $this CrudController */
/* @var $model CActiveDataProvider */

$this->pageTitle = "Список понятий тезауруса";

$this->widget("zii.widgets.grid.CGridView", array(
    "dataProvider" => $model,
    "columns" => array(
        "id",
        "name",
        array(
            "name" => "Синонимы",
            "value" => '$data->getListNamesWordsToString()',
        ),
        array(
            "class" => "CButtonColumn",
            "header" => "Действия",
            "template" => "{update} {delete}"
        ),
    ),
));

?>