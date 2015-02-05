<?php

class SymbolController extends CrudController
{
    public function actionView($id) {
        throw new CHttpException(400);
    }
    
    public function actionUpdate($id) {
        if (!is_null($id)) {
            $this->parsingWords($id);
        }
        parent::actionUpdate($id);
    }
    
    /**
     * Разборка входной фразы на состовляющие
     * @param integer $id_symbol
     */
    private function parsingWords($id_symbol) {
        if (isset($_POST['Symbol']["words"])) {
            Word::removeAllWordsForSymbol($id_symbol);
            $words = split(",", $_POST['Symbol']["words"]);
            foreach ($words as $word) {
                if ($word == "") {
                    continue;
                }
                if (!Word::exist($word)) {
                    $new_word = new Word();
                    $new_word->id_symbol = $id_symbol;
                    $new_word->value = $word;
                    $new_word->save();
                }
            }
        }
    }
    
    public function getClass() {
        return new Symbol();
    }
}