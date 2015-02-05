<?php
/* @var $this CrudController */
/* @var $model Hypothesis */
/* @var $form CActiveForm */

$this->pageTitle = ($model->isNewRecord ? "Добавление" : "Редактирование"). " гипотезы";
?>

<h1><?=$this->pageTitle?></h1>

<div class="yii-form">

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=> get_class($model),
)); ?>

    <div class="row">
        <?php echo $form->label($model,'name'); ?>
        <?php echo $form->textField($model,'name'); ?>
        <?php echo $form->error($model,'name'); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'odds'); ?>
        <?php echo $form->textField($model,'odds'); ?>
        <?php echo $form->error($model,'odds'); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model,'c_value'); ?>
        <?php echo $form->textField($model,'c_value'); ?>
        <?php echo $form->error($model,'c_value'); ?>
    </div>
    
    <div class="row">
        <?php if (!$model->isNewRecord) {
            $this->widget("afq", array("id_quest" => $model->id));
        } ?>
    </div>
    
    <div class="row buttons">
        <?php echo CHtml::submitButton('Submit'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->