<?php
/* @var $this SiteController */
/* @var $model array в формате */
?>

<div class="page-header">
    <h1>Результаты тестирования</h1>
</div>

<script>
    
    function setScale(c_value) {
        if (c_value < 0) {
            alert("Оценка 'неудовлетворительно'");
            return;
        }
        var scale = prompt("Введите бальность");
        scale = Number(scale);
        if (!!scale) {
            var h = 5 / scale;
            var ex = Math.ceil(Number(c_value) / h);
            ex = (ex > scale) ? scale : ex;
            alert("Оценка "+ ex);
        }
    }
    
</script>

<p>
    <table width="700px">
        <thead>
            <tr>
                <th>Наименование гипотезы</th>
                <th>Значение веса</th>
                <th>Выбор оценок</th>
            </tr>
        </thead>
        <tbody>
            <?php

            foreach ($model as $name => $c_value) {
                echo "<tr>";
                echo "<td>{$name}</td>";
                echo "<td>". round($c_value, 3) ."</td>";
                echo "<td><input type='button' value='Шкала' onclick='setScale({$c_value})' /></td>";
                echo "</tr>";
            }

            ?>
        </tbody>
    </table>
</p>

<p>
    <?=CHtml::link(CHtml::button("Начать заново", array("class" => "btn btn-success")), $this->createAbsoluteUrl("site/start"))?>
</p>