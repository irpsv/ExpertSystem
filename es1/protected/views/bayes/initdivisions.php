<?php
/* @var $this BayesController */
/* @var $divisions array */
/* @var $alters array */

$defaultValue = 0.0;
$htmlOptions = array('style' => "width:80px;margin:auto");

?>

<div class="page-header">
    <h1>2. Установка граничных значений</h1>
</div>

<form action="" method="POST">

    <p>
<table width="700px">
    <tr>
        <th>Раздел</th>
        <?php foreach ($alters as $name): ?>
        <th><?=$name?></th>
        <?php endforeach; ?>
    </tr>
    <?php
    
    $alters_keys = array_keys($alters);
    
    ?>
    <tr>
        <td>Все разделы</td>
        <?php foreach ($alters_keys as $id_alter): ?>
        <td>
            <?=CHtml::textField("Division[div0alt{$id_alter}]", $defaultValue, $htmlOptions)?>
        </td>
        <?php endforeach; ?>
    </tr>
    <?php foreach ($divisions as $id_division => $nameDiv): ?>
    <tr>
        <td>
            <?=$nameDiv?>
        </td>
        <?php foreach ($alters_keys as $id_alter): ?>
        <td>
            <?=CHtml::textField("Division[div{$id_division}alt{$id_alter}]", $defaultValue, $htmlOptions)?>
        </td>
        <?php endforeach; ?>
    </tr>
    <?php endforeach; ?>
</table>
    </p>
    
    <p>
<?=CHtml::submitButton('Далее', array("class" => "btn btn-success"))?>
        </p>
    
</form>