<?php

class HypothesisController extends CrudController
{
    public function actionView($id) {
        throw new CHttpException(400);
    }
    
    public function actionUpdate($id) {
        $this->updateAnswers($id);
        parent::actionUpdate($id);
    }
    
    private function updateAnswers($id) {
        if (!is_null($id) && isset($_POST['Answer'])) {
            $answers = $_POST['Answer'];
            foreach ($answers as $id_answer => $data) {
                $val = floatval($data['val']);
                $answer = $this->loadAnswer($id_answer);
                $answer->id_hypothesis = $id;
                $answer->name = $data['name'];
                $answer->c_value = ($val > 5) ? 5 : (($val < -5) ? -5 : $val);
                $answer->save();
            }
        }
    }
    
    /**
     * Выгружает ответ с указанным ID, либо создает запись с данным ID
     * @param integer $id_answer
     */
    private function loadAnswer($id_answer) {
        $answer = Answer::model()->findByPk($id_answer);
        if (is_null($answer)) {
            $answer = new Answer();
            $answer->id = $id_answer;
            $answer->id_hypothesis = 0;
            $answer->name = "";
            $answer->c_value = 0.0;
            $answer->save();
        }
        return $answer;
    }
    
    public function getClass() {
        return new Hypothesis();
    }
}