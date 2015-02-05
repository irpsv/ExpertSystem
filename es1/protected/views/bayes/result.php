<?php
/* @var $this BayesController */
/* @var $alters array в формате: [name] => [prob] */
/* @var $divisions array */

$namesAlters = array_keys($alters);

Yii::app()->clientScript->registerCoreScript("jquery");

?>

<div class="page-header">
    <h1>Результаты тестирования</h1>
</div>

<script>
    function setScaleEval(row) {
        var scale = 4;
        scale = prompt("Введите бальность оценки");
        scale = Number(scale);
        if (!!scale) {
            var oldValues = getValuesCells(row);
            var params = calcParamsLines(oldValues);
            var h = 3.0 / (scale - 1);
            var newValues = calcNewValues(params, h);
            newValues = normalize(newValues);
            alert(newValues);
        }
    }
    
    /**
     * Возвращает список значение в строке таблице (кроме 1 и последней ячейки)
     */
    function getValuesCells(row) {
        var tds = $(row).children("td");
        var values = [];
        for (var i=1; i<tds.length - 1; i++) {
            values.push(Number($(tds[i]).html()));
        }
        return values;
    }
    
    /**
     * Вычисляет параметры прямых для уравнения
     * @param {Array} values список значений для прямой
     */
    function calcParamsLines(values) {
        var params = [];
        for (var i = 0, max = 3; i < max; i++) {
            var y1 = values[i];
            var y2 = values[i+1];
            var x1 = i;
            var x2 = i+1;
            //
            var a = (y2 - y1) / (x2 - x1);
            var b = y1 + 2 - a * x1;
            params[i] = { a: a, b:b };
        }
        return params;
    }
    
    /**
     * Вычисление новых значений
     * @param {Array} oldValues
     * @param {Array} params
     * @param {Number} h
     */
    function calcNewValues(params, h) {
        var values = [];
        var x = 0.0;
        var i = 1;
        while(true) {
            if (x > i)
                i++;
            var a = params[i-1].a;
            var b = params[i-1].b;
            var y = a * x + b - 2;
            values.push(y);
            if (x == 3 || (x + h) > 3) {
                break;
            }
            x += h;
        }
        return values;
    }
    
    function normalize(arr) {
        var x = sum(arr);
        for (var i = 0, max = arr.length; i < max; i++) {
            arr[i] = (arr[i] / x).toFixed(3);
        }
        return arr;
    }
    
    function sum(arr) {
        var x = 0.0;
        for (var i = 0, max = arr.length; i < max; i++) {
            x += arr[i];
        }
        return x;
    }
</script>

<P>
<table width="700px">
    <tr>
        <th>Раздел</th>
        <?php foreach ($namesAlters as $name): ?>
            <th><?=$name?></th>
        <?php endforeach; ?>
    </tr>
<?php foreach ($divisions as $divName => $data): ?>
    <tr>
        <td>
            <?=$divName?>
        </td>
        <?php foreach ($namesAlters as $altName) : ?>
            <td>
                <?php
                
                    echo round($data[$altName]['prob'], 3);
                
                ?>
            </td>
        <?php endforeach; ?>
        <td>
            <input type="button" onclick="setScaleEval(this.parentNode.parentNode)" value="Шкала" />
        </td>
    </tr>
<?php endforeach; ?>
</table>
</p>

<p>
    <?=CHtml::link(CHtml::button("Начать заново", array("class" => "btn btn-success")), $this->createAbsoluteUrl('bayes/start'))?>
</p>