<?php

Yii::import("prospector.models.*");

/**
 * Производит вычисления параметров прямой для PROSPECTOR
 */
class CalcController extends CController
{
    public function actionIndex() {
        /* @var $h Hypothesis */
        foreach (Hypothesis::model()->findAll() as $h) {
            if (empty($h->childLinks)) {
                continue;
            }
            $kmax = 1.0;
            $kmin = 1.0;
            foreach ($h->childLinks as $link) {
                $kmax *= doubleval($link->LS);
                $kmin *= doubleval($link->LN);
            }
            $a = 10.0 / (log($kmax) - log($kmin));
            $b = 5 - $a * log($kmax);
            $this->updateRecord($h->id, $a, $b);
        }
        echo "Calc'ing ended!";
    }

    /**
     * Обновляет или добавляет запись в таблицу для указанной гипотезы
     * @param type $id_hypothesis
     * @param type $a
     * @param type $b
     */
    private function updateRecord($id_hypothesis, $a, $b) {
        $sql = "SELECT * FROM ArgumentsHypothesis WHERE id_hypothesis = {$id_hypothesis}";
        if (Yii::app()->db->createCommand($sql)->queryRow() != FALSE) {
            // UPDATE
            $sql = "UPDATE ArgumentsHypothesis SET id_hypothesis = ?, a = ?, b = ? WHERE id_hypothesis = {$id_hypothesis}";
        }
        else {
            // INSERT
            $sql = "INSERT INTO ArgumentsHypothesis(id_hypothesis,a,b) VALUES(?, ?, ?)";
        }
        Yii::app()->db->createCommand($sql)->execute(array($id_hypothesis, $a, $b));
    }
}