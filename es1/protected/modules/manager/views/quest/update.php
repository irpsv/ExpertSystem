<?php
/* @var $this CController */
/* @var $form CActiveForm */
/* @var $model CActiveRecord */
/* @var $errors array */

$title = ($model->isNewRecord) ? "Добавление" : "Редактирование";
$title .= " вопроса";
$this->pageTitle = $title;

if (!empty($errors)) {
    echo "<div class='erorrs-info'>". implode("<br>", $errors) ."</div>";
}

?>

<h1><?php echo $title; ?></h1>

<?php

$form = $this->beginWidget('CActiveForm', array(
    'id'=> get_class($model),
    'enableAjaxValidation'=>true,
));

$htmlOptions = array("class" => "form-control");

echo $form->label($model, "name");
echo $form->textField($model, "name", $htmlOptions);

echo $form->label($model, "divisions");
$this->widget('ListDivisions', array(
    "divisions" => $model->divisions,
));

echo CHtml::label('Ответы', null);
$this->widget("TableAnswers", array(
    "answers" => $model->answers,
));

$submitLabel = ($model->isNewRecord) ? 'Добавить' : 'Сохранить';
echo CHtml::submitButton($submitLabel, array(
    "class" => "btn btn-success",
));
echo " ";
echo CHtml::resetButton("Сбросить", array(
    "class" => "btn btn-danger",
));

$this->endWidget();

?>