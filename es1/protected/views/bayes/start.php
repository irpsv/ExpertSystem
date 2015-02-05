<?php
/* @var $this SiteController */
/* @var $model array в формате 'id' => 'понятие' */

$action = $this->createAbsoluteUrl("bayes/initdivisions");

?>

<div class="page-header">
    <h1>1. Выберите список понятий для тестирования</h1>
</div>

<form action="<?=$action?>" method="POST">
    
    <p>
    <?php
    
    foreach ($model as $id => $symbol) {
        $id_html = "symbol-{$id}";
        echo CHtml::checkBox("symbols[]", false, array(
            "id" => $id_html, "value" => $id
        ));
        echo CHtml::label($symbol, $id_html);
        echo "<br>";
    }
    
    ?>
    </p>
    
    <p>
        <input class="btn btn-success" type="submit" value="Далее" />
    </p>
    
</form>