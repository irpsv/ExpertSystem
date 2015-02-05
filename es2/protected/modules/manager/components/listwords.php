<?php

/**
 * Список слов для понятия
 */
class listwords extends CWidget
{
    public $symbol;
    
    public function run() {
        $words = array();
        if (!$this->symbol->isNewRecord) {
            foreach (Word::listValues($this->symbol->id) as $word) {
                $words[] = $word;
            }
        }
        $this->render('listwords', array(
            "words" => implode(",", $words)
        ));
    }
}