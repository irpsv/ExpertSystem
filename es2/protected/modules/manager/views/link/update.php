<?php
/* @var $this CrudController */
/* @var $model Link */
/* @var $form CActiveForm */

$listhypotheses = Hypothesis::listNames();

$htmlOptions = array(
    "size" => 1,
);

$this->pageTitle = ($model->isNewRecord ? "Добавление" : "Редактирование"). " связей";

?>

<h1><?=$this->pageTitle?></h1>

<div class="yii-form">

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'link-f-form',
    'enableAjaxValidation'=>false,
)); ?>

    <div class="row">
        <?php echo $form->label($model,'id_parent'); ?>
        <?php echo $form->listBox($model, 'id_parent', $listhypotheses, $htmlOptions); ?>
        <?php echo $form->error($model,'id_parent'); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'id_type'); ?>
        <?php echo $form->listBox($model, 'id_type', TypeLink::listNames(), $htmlOptions); ?>
        <?php echo $form->error($model,'id_type'); ?>
    </div>
    
    <div class="row">
        <?php 
            echo CHtml::label("Связи", "");
            echo CHtml::listBox("ChildLinks", $model->getChildHyps(true), $listhypotheses, array("multiple" => true, "size" => 10));
        ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'LN'); ?>
        <?php echo $form->textField($model,'LN'); ?>
        <?php echo $form->error($model,'LN'); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'LS'); ?>
        <?php echo $form->textField($model,'LS'); ?>
        <?php echo $form->error($model,'LS'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Submit'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->