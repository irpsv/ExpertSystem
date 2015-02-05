/**
 * Начало работы ЭС
 */
function startExpert() {
    $.ajax('reset', {
        success: function() {
            $("#questions").html("");
            $("#start-btn").val('Заново');
            nextQuest();
        },
    });
}

/**
 * Обрабатывает результаты ответа пользователя
 * @param {string} nameInput атрибут 'name' тэга который содержит ответ на вопрос
 */
function sendAnswer(nameInput) {
    var selector = "[name="+ nameInput +"]:checked";
    var id_answer = $(selector).val();
    if (!!!id_answer) {
            alert('Дайте ответ!'); return;
    }
    var url = "aa?id_answer="+ id_answer;
    //
    $.ajax(url, {
        success: function() {
            // блокировка полей
            $(selector).parent().parent().each(function(index){
                $(this).attr({
                    'disabled': 'true',
                })
            });
            nextQuest();
        },
    });
}

/**
 * Получает ответ от системы
 */
function nextQuest() {
    var out = '#questions';
    $.ajax('nq', {
        success: function(data) {
            $('.btn-apply-answer').remove();
            $(out).append(data);
            updateInfoAlters();
			// прокрутка до элемента
			var y = $('.quest-wrap').last().offset().top - 5;
			window.scrollTo(0, y);
        },
    });
}

/**
 * Отрисовка таблицы результатов
 */
function updateInfoAlters() {
    $.ajax('pa', {
        success: function(data) {
            var alters = $.parseJSON(data);
            for (id_alter in alters) {
                var id = '#alter-'+ id_alter +'-prob';
                var value = Number(alters[id_alter]) * 100;
                $(id).html(value.toFixed() + '%');
            }
        },
    });
}