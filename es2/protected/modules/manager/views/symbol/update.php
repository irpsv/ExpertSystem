<?php
/* @var $this CrudController */
/* @var $model Symbol */
/* @var $form CActiveForm */

$this->pageTitle = ($model->isNewRecord ? "Добавление" : "Редактирование"). " понятия";

?>

<h1><?=$this->pageTitle?></h1>

<div class="yii-form">

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>get_class($model),
    'enableAjaxValidation'=>false,
)); ?>

    <div class="row">
        <?php echo $form->label($model,'name'); ?>
        <?php echo $form->textField($model,'name'); ?>
        <?php echo $form->error($model,'name'); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'desc'); ?>
        <?php echo $form->textField($model,'desc'); ?>
        <?php echo $form->error($model,'desc'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->label($model,'id_parent'); ?>
        <?php echo $form->listBox($model,'id_parent', Symbol::listNames()); ?>
        <?php echo $form->error($model,'id_parent'); ?>
    </div>
    
    <?php if (!$model->isNewRecord): ?>
    <div class="row">
        <?php $this->widget("listwords", array("symbol" => $model)) ?>
    </div>
    <?php endif; ?>
    
    <div class="row buttons">
        <?php echo CHtml::submitButton('Submit'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->