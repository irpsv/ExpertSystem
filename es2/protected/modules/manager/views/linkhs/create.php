<?php
/* @var $this LinkshController */
/* @var $model LinkhsForm */
/* @var $form CActiveForm */

$htmlOptions = array(
    "size" => 1,
);

$this->pageTitle = "Добавление связей между понятиями";

?>

<h1><?=$this->pageTitle?></h1>

<div class="yii-form">

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>  get_class($model),
    'enableAjaxValidation'=>false,
)); ?>

    <div class="row">
        <?php echo $form->label($model,'id_hypothesis'); ?>
        <?php echo $form->listBox($model, 'id_hypothesis', $model->getHypothesis(), $htmlOptions); ?>
        <?php echo $form->error($model,'id_hypothesis'); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'list_symbols'); ?>
        <?php echo $form->listBox($model, 'list_symbols', $model->getSymbols(), array("size" => 5, "multiple" => true)); ?>
        <?php echo $form->error($model,'list_symbols'); ?>
    </div>
    
    <div class="row buttons">
        <?php echo CHtml::submitButton('Submit'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->