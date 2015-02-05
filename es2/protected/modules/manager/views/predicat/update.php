<?php
/* @var $this CrudController */
/* @var $model Predicat */
/* @var $form CActiveForm */

$this->pageTitle = ($model->isNewRecord ? "Добавление" : "Редактирование"). " связей тезауруса";

$symbolsNames = Symbol::listNames();

?>

<h1><?=$this->pageTitle?></h1>

<div class="yii-form">

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>  get_class($model),
    'enableAjaxValidation'=>false,
)); ?>
    
    <?php if (!$model->isNewRecord): ?>
    
    <div class="row">
        Первый аргумент: 
        <?php
            echo CHtml::listBox("Argument[0]", $model->getFirstArg(), $symbolsNames, array("size" => 1));
        ?>
    </div>
    
    <?php endif; ?>
    
    <div class="row">
        <?php echo $form->label($model,'name'); ?>
        <?php echo $form->textField($model,'name'); ?>
        <?php echo $form->error($model,'name'); ?>
    </div>
    
    <?php if (!$model->isNewRecord): ?>
    
    <div class="row">
        Второй аргумент: 
        <?php
            echo CHtml::listBox("Argument[1]", $model->getSecondArg(), $symbolsNames, array("size" => 1));
        ?>
    </div>
    
    <?php endif; ?>
    
    <div class="row buttons">
        <?php echo CHtml::submitButton('Submit'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->