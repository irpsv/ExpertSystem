<?php
/* @var $this TableAnswers */
/* @var $alters Alternative[] */

$altersId = array();
$headerTable = "";

foreach ($alters as $alter) {
    $altersId[] = $alter->id;
    $headerTable .= "<th id='alter-{$alter->id}'>{$alter->name}</th>";
}

?>

<script>
    alters = [<?php echo implode(",", $altersId); ?>];
    
    /**
     * Добавление ответа в поле таблицы
     */
    function addAsnwerInTableAnswers() {
        var id = Number($("[name=answer-id-max]").val()) + 1;
        var tr = document.createElement('tr');
            tr.setAttribute('id', id);
        // кнопка удаления
        var delButton = document.createElement('button');
            delButton.setAttribute('class', 'remove-button');
            delButton.onclick = function() { delAnswer(this); };
            delButton.innerHTML = "<span class='glyphicon glyphicon-remove'></span>";
        var td1 = document.createElement('td');
            td1.appendChild(delButton);
        tr.appendChild(td1);
        // наименование ответа
        var nameText = document.createElement('input');
            nameText.setAttribute('class', 'form-control');
            nameText.type = 'text';
            nameText.name = "Answer["+ id +"][name]";
        var td2 = document.createElement('td');
            td2.appendChild(nameText);
        tr.appendChild(td2);
        // значения альтернатив
        for (var i=0; i<alters.length; i++) {
            var probText = document.createElement('input');
                probText.setAttribute('class', 'form-control');
                probText.type = 'text';
                probText.name = "Answer["+ id +"][alter"+ (i+1) +"]";
            var tdN = document.createElement('td');
                tdN.appendChild(probText);
            tr.appendChild(tdN);
        }
        $("#table-answers").append(tr);
        $("[name=answer-id-max]").val(id);
    }
    
    /**
     * Удаление из таблицы указанного ответа
     * @param {DOMElement} objButton
     */
    function delAnswer(objButton) {
        // button > td > tr - удаляем строку из таблицы
        var tr = $(objButton).parent().parent();
        var val = $("[name=delete-answers]").val();
        $("[name=delete-answers]").val(val + ";" + tr.attr('id'));
        tr.remove();
    }
</script>

<input type="button" class="btn btn-default" onclick="addAsnwerInTableAnswers()" value="Добавить"/>
<input type="hidden" name="answer-id-max" value="0" />
<input type="hidden" name="delete-answers" value="" />

<table id="table-answers" class="table table-bordered">
    <!-- заголовки = альтернативы -->
    <thead>
    <tr>
        <?php
        
        echo "<th width='60px'> </th>";
        echo "<th>Ответ</th>";
        echo $headerTable;
        
        ?>
    </tr>
    </thead>
    <!-- содержание = вероятности -->
    <tbody>
        <?php
        
        /* @var $answer Answer */
        if (!empty($this->answers)) {
            foreach ($this->answers as $answer) {
                echo "<tr id='{$answer->id}'>";
                echo "<td><button class='remove-button' onclick='delAnswer(this);'><span class='glyphicon glyphicon-remove'></span></button></td>";
                // поле для наименования ответа
                $name = "Answer[{$answer->id}][name]";
                $value = $answer->name;
                echo "<td><input class='form-control' type='text' name='{$name}' value='{$value}' /></td>";
                // вероятности для ответа
                foreach ($altersId as $id_alter) {
                    foreach ($answer->probabilities as $prob) {
                        if ($id_alter != $prob->id_alter)
                            continue;
                        $name = "Answer[{$answer->id}][alter{$id_alter}]";
                        $value = $prob->value;
                        echo "<td><input type='text' class='form-control' name='{$name}' value='{$value}' /></td>";
                        break;
                    }
                }
                echo "</td>";
            }
            // вычисляем максимальный ID альтернативы
            $row = Yii::app()->db->createCommand("SELECT MAX(id) AS [id] FROM Answer")->queryRow();
            $row['id'] += 1;
            echo "<script>$('[name=answer-id-max]').val({$row['id']});</script>";
        }
        
        ?>
    </tbody>
</table>