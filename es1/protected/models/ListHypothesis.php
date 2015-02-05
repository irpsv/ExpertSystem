<?php

/**
 * Description of ListHypothesis
 *
 */
class ListHypothesis extends CFormModel
{
    private $_id_symbols;
    private $_hypothesis;
    
    public function setSymbols(array $value) {
        $this->_id_symbols = $value;
    }
    
    public function getHypothesis() {
        if (is_null($this->_hypothesis)) {
            $this->_hypothesis = array();
            foreach ($this->_id_symbols as $id_symbol) {
                $sql = "SELECT id_division FROM DS WHERE id_symbol = {$id_symbol}";
                $rows = Yii::app()->db->createCommand($sql)->queryAll();
                foreach ($rows as $row) {
                    $h = Division::model()->findByPk($row['id_division']);
                    $this->_hypothesis[$h->id] = $h->name;
                }
                unset($rows);
            }
        }
        return $this->_hypothesis;
    }
}