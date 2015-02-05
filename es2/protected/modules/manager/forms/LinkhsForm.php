<?php

Yii::import("prospector.models.Hypothesis");
Yii::import("tezaurus.models.Symbol");

/**
 * Форма для установки связи между Гипотезой и понятиями
 */
class LinkhsForm extends CFormModel
{
    //
    // ЛОГИКА
    //
    
    /**
     * Добавляет / обновляет данные
     */
    public function save() {
        if (Symbol::existLinkOfHypothesis($this->id_hypothesis)) {
            $this->deleteOldRecords();
        }
        if (!empty($this->list_symbols)) {
            $this->insertNewRecords();
        }
    }
    
    /**
     * Удаляет записи о связях гипотезы с понятием
     */
    private function deleteOldRecords() {
        $sql = "DELETE FROM HS WHERE id_hypothesis = {$this->id_hypothesis}";
        Yii::app()->db->createCommand($sql)->execute();
    }
    
    /**
     * Добавляет записи о связи гипотезы с понятиями
     */
    private function insertNewRecords() {
        $db = Yii::app()->db;
        $tran = $db->beginTransaction();
        try {
            foreach ($this->list_symbols as $id_symbol) {
                $sql = "INSERT INTO HS VALUES(:id_hyp, :id_sym)";
                $cmd = $db->createCommand($sql);
                $cmd->bindValue(":id_hyp", $this->id_hypothesis);
                $cmd->bindValue(":id_sym", $id_symbol);
                $cmd->execute();
            }
            $tran->commit();
        }
        catch (Exception $ex) {
            $tran->rollback();
        }
    }
    
    //
    // ДАННЫЕ
    //
    
    public $id_hypothesis;
    public $list_symbols;
    
    public function getHypothesis() {
        return Hypothesis::listNames();
    }
    
    public function getSymbols() {
        return Symbol::listNames();
    }
    
    public function rules() {
        return array(
            array("id_hypothesis, list_symbols", "required"),
            array("id_hypothesis", "numerical", "integerOnly" => true),
        );
    }
    
    public function attributeLabels() {
        return array(
            "id_hypothesis" => "Гипотеза",
            "list_symbols" => "Список понятий",
        );
    }
}