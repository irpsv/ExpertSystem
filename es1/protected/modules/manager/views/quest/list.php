<?php
/* @var $this CController */
/* @var $model CActiveDataProvider */

$this->widget('zii.widgets.grid.CGridView', array(
    "dataProvider" => $model,
    "columns" => array(
        'name',
        array(
            "class" => "CButtonColumn",
            "template" => "{update} {delete}",
        ),
    ),
));