<?php

/**
 * Таблица ответов для добавления вопроса
 */
class TableAnswers extends CWidget
{
    /**
     * Список ответов для записи
     * @var Answer[]
     */
    public $answers;
    
    public function run()
    {
        $this->render('tableanswers', array(
            "alters" => Alternative::model()->findAll(),
        ));
    }
}