<?php

class QuestController extends CrudController {
    public function getClass() {
        return new Quest();
    }
    
    public function actionTest() {
        $this->render('test');
    }
    
    /**
     * Редактирование / добавление записи
     * @param integer $id идентификатор записи
     */
    public function actionUpdate($id)
    {
        $errors = array();
        $quest = $this->loadModel($id);
        if ($this->initForm($quest) && $quest->save()
                && $this->validateAnswers($_POST['Answer'], $errors))
        {
            $this->processingAnswers($quest->id, $_POST['Answer']);
            $this->addDivisions($quest->id);
            $this->redirect(array('/manager/quest/update', 'id' => $quest->id));
        }
        $this->render('update', array("model" => $quest, "errors" => $errors));
    }
    
    public function actionDelete($id) {
        /* @var $quest Quest */
        $quest = $this->loadModel($id);
        if (!$quest->isNewRecord) {
            /* @var $answer Answer */
            foreach ($quest->answers as $answer) {
                foreach ($answer->probabilities as $prob) {
                    $prob->delete();
                }
                $answer->delete();
            }
            $quest->delete();
        }
        $this->redirect(array('/manager/quest/list'));
    }
    
    /**
     * Обработка ответов с вероятностями и их сохранение
     * @param integer $id_quest ID вопроса
     * @param array $answersAndProbs массив ответов на вопрос с вероятностями в формате:
     *      array(
     *          'id_answer' => 'name',      // наименование ответа
     *          'id_answer' => 'alter[X]',    // вероятность для указанной альтернативы
     *      )
     */
    private function processingAnswers($id_quest, array $answersAndProbs) {
        foreach ($answersAndProbs as $id_answer => $data) {
            $answer = Answer::create($id_answer);
            $answer->id_quest = $id_quest;
            foreach ($data as $key => $value) {
                if ($key == 'name') {
                    $answer->name = $value;
                }
                else {
                    if ($answer->isNewRecord)
                        $answer->save();
                    preg_match("/alter(\\d+)/", $key, $result);
                    $id_alter = (int) $result[1];
                    $probability = Probability::create($answer->id, $id_alter);
                    $probability->value = $value;
                    $probability->save();
                }
            }
        }
        if (isset($_POST['delete-answers']))
            $this->deleteAnswers($_POST['delete-answers']);
    }

    /**
     * Добавляет указанные разделы для выбранного вопроса
     */
    private function addDivisions($id_quest) {
        if ($this->removeDivisions($id_quest) && isset($_POST['Division'])) {
            foreach (array_keys($_POST['Division']) as $id_division) {
                $sql = 'INSERT INTO DivisionQuest(id_quest, id_division) VALUES(:idq, :idd)';
                $cmd = Yii::app()->db->createCommand($sql);
                $cmd->bindParam(':idq', $id_quest, PDO::PARAM_INT);
                $cmd->bindParam(':idd', $id_division, PDO::PARAM_INT);
                $cmd->execute();
                unset($cmd);
            }
        }
    }
    
    /**
     * Удаляет все разделы выбранного вопроса
     */
    private function removeDivisions($id_quest) {
        $sql = 'DELETE FROM DivisionQuest WHERE id_quest = '. $id_quest;
        Yii::app()->db->createCommand($sql)->execute();
        return true;
    }
    
    /**
     * Валидация значений
     * @param array $answers
     */
    public function validateAnswers(array $answers, &$errors) {
        foreach ($answers as $id_answer => $data) {
            foreach ($data as $key => $value) {
                if ($key != 'name'
                        && (!is_numeric($value) || $value < 0.0 || $value > 1.0)) {
                    $errors[] = "Значение вероятностей должно быть число в диапазоне от 0.0 до 1.0";
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Удаляет ответы и вероятности, которые были удалены
     * @param string $ids_answers_to_str
     */
    public function deleteAnswers($ids_answers_to_str) {
        preg_match_all("/;(\\d+)/", $ids_answers_to_str, $ids_delete_answers);
        if (!isset($ids_delete_answers[1])) {
            return;
        }
        foreach ($ids_delete_answers[1] as $id_answer) {
            /* @var $answer Answer */
            if (is_null($answer = Answer::model()->findByPk($id_answer))) {
                continue;
            }
            foreach ($answer->probabilities as $prob) {
                $prob->delete();
            }
            $answer->delete();
        }
    }
}