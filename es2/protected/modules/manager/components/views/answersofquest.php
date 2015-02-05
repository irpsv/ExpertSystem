<?php
/* @var $this afq */
/* @var $answers Answer[] */
/* @var $id_last_answer integer первый доступный идентификатор */

Yii::app()->clientScript->registerCoreScript('jquery');

?>

<input id="id-last-answer" type="hidden" value="<?=$id_last_answer?>" />
<div class="list-answers">
    <div class="manager-bar">
        <input type="button" id="but-add-item-to-list" value="Добавить ответ на вопрос" />
    </div>
    <div id="list-data">
        <?php if (!empty($answers)) { foreach ($answers as $a) :?>
        <p>
            Ответ: <input type="text" name="Answer[<?=$a->id?>][name]" value="<?=$a->name?>" />, 
            Вес (от -5 до 5): <input type="text" name="Answer[<?=$a->id?>][val]" value="<?=$a->c_value?>" />
        </p>
        <?php endforeach; } ?>
    </div>
</div>

<script>
(function(){
    $('#but-add-item-to-list').click(function(){
        var id = $("#id-last-answer").val();
        var pre = "Answer["+id+"]";
        var text = document.createElement('input');
            text.name = pre+"[name]";
        var value = document.createElement('input');
            value.name = pre+"[val]";
            value.value = 0.0;
        var span = document.createElement('span');
            span.innerHTML = ", Вес (от -5 до 5): ";
            span.appendChild(value);
        var p = document.createElement("p");
            p.innerHTML = "Ответ: ";
            p.appendChild(text);
            p.appendChild(span);
        $("#list-data").append(p);
        $("#id-last-answer").val(Number(id) + 1);
    });
})();
</script>