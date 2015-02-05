<?php

/**
 * Answers Of Quest
 * 
 * Ответы для вопроса
 */
class afq extends CWidget
{
    public $id_quest;
    
    public function run() {
        $answers = Quest::getAnswersEx($this->id_quest);
        $this->render('answersofquest', array(
            "answers" => $answers,
            "id_last_answer" => Answer::getMaxId() + 1)
        );
    }
}