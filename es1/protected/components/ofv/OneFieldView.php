<?php
/* @var $this CController */
/* @var $model CActiveRecord[] */

$model = Alternative::model()->findAll();

Yii::app()->clientScript->registerCssFile(
    Yii::app()->baseUrl .'/css/ofv.css'
);
?>

<script>
    function edit() {
        $("#list-model").val();
    }
</script>

<div class="ofv-main">
    <div class="ofv-name-model">
        <?php echo get_class(is_array($model) ? $model[0] : $model); ?>
    </div>
    <hr>
    <div class="ofv-block">
        <?php
        $data = array();
        foreach ($model as $item) {
            $data[$item->id] = $item->name;
        };
        echo CHtml::listBox("name", "", $data, array(
            "id" => "list-model",
            "class" => "selectpicker",
            "data-width" => "250px",
        ));
        ?>
        <button class="btn btn-default" onclick="edit();">
            <span class="glyphicon glyphicon-pencil"></span>
        </button>
    </div>
    <hr>
    <div class="ofv-block form-inline">
        <input id="ofv-edit-field" type="text" class="form-control" style="width: 250px;"/>
        <button class="btn btn-default">
            <span class="glyphicon glyphicon-plus"></span>
        </button>
    </div>
    <hr>
    <div class="ofv-buttons">
        <button class="btn btn-success">Выбрать</button>
        <button class="btn btn-danger">Отмена</button>
    </div>
</div>