<?php

/**
 * Класс который хранит и оперирует знаниями используя логику теоремы Байеса
 */
class BayesClass {
    public $questions;
    public $id_lastanswer;
    /** список разделов в формате:
     *  array(
     *      'id раздела' => array(
     *          'id альтернативы' => array(
     *              'eps' => 'пороговая вероятность',
     *              'prob' => 'текущая вероятность',
     *          ),
     *      ),
     *      ...
     *  )
     * Для всех альетрнатив используется индекс 0
     *  **/
    public $divisions;
    /**
     * @return array массив в формате: 'id_alter' => 'prob'
     */
    public function getAlternatives() {
        $result = array();
        foreach ($this->divisions[0] as $id_alter => $data) {
            $result[$id_alter] = $data['prob'];
        }
        return $result;
    }
    public function setAlternatives($value) {
        $ids_alters = isset($this->divisions[0])
                ? array_keys($this->divisions[0])
                : array_keys(Alternative::getNames());
        foreach ($ids_alters as $id_alter) {
            $this->divisions[0][$id_alter]['prob'] = $value[$id_alter];
        }
    }
    
    /**
     * Возвращает ID раздела, для которого нужно задать вопрос
     */
    public function getIdNeededDivision() {
        $id_div_result = -1;
        // максимальная разница между текущей вероятностю раздела
        // и необходимой вероятностью данного раздела
        $max_diff = 0.0;
        foreach ($this->divisions as $id_div => $alters) {
            if ($id_div == 0) {
                continue;
            }
            foreach ($alters as $id_alter => $data) {
                if (($p = floatval($data['prob'])) != 0.0) {
                    $diff = floatval($data['eps']) - $p;
                    $diff = $diff < 0.0 ? 0 : $diff;
                    if ($diff > $max_diff) {
                        $max_diff = $diff;
                        $id_div_result = $id_div;
                    }
                }
            }
        }
        return $id_div_result;
    }
    
    /**
     * Проверят все ли альтернативы достигли нужной вероятности
     */
    public function isEndTest() {
        if (empty($this->questions)) {
            return false;
        }
        foreach ($this->divisions as $div) {
            foreach ($div as $data) {
                if (floatval($data['eps']) != floatval('0') && $data['prob'] < $data['eps']) {
                    return false;
                }
            }
        }
        return true;
    }
    
    /**
     * Инициализирует массив разделов
     * @param array $divisions массив заданных точностей для разделов
     */
    public function initDivisions(array $divisions) {
        $this->divisions = array();
        $alters = array();
        $ids_alters = array_keys(Alternative::getNames());
        $prob = 1.0 / count($ids_alters);
        foreach ($ids_alters as $id_alter) {
            $alters[$id_alter] = $prob;
        }
        foreach ($divisions as $name => $eps) {
            preg_match("/div(\\d+)alt(\\d+)/", $name, $mathed);
            $id_division = $mathed[1];
            $id_alter = $mathed[2];
            if (!isset($this->divisions[$id_division])) {
                $this->divisions[$id_division] = array();
            }
            $this->divisions[$id_division][$id_alter] = array(
                'prob' => $alters[$id_alter],
                'eps' => (double) $eps
            );
        }
    }
    
    /**
     * Заполняет массив разделов
     * @param int $id_answer
     */
    private function updateDivisions($id_quest, $id_answer) {
        $ids_divisions = Quest::getDivisions($id_quest);
        $ids_divisions[] = 0;
        foreach ($ids_divisions as $id_division) {
            if (isset($this->divisions[$id_division])) {
                Answer::model()->findByPk($id_answer)->initAltersEx($this->divisions[$id_division]);
            }
        }
    }
    
    /**
     * Возвращает ID лидирующей альтернативы для указанного раздела
     * @param int $id_division
     */
    private function getIdLeaderAlterInDivision($id_division = null) {
        $id_leader = null;
        $max = 0.0;
        $alters = is_null($id_division) ? $this->getAlternatives() : $this->divisions[$id_division];
        foreach ($alters as $id_alter => $prob) {
            if ($prob > $max) {
                $max = $prob;
                $id_leader = $id_alter;
            }
        }
        return $id_leader;
    }
    
    /**
     * Возвращает последний ответ
     */
    private function getLastAnswer()
    {
        if (isset($this->id_lastanswer))
            return Answer::model()->findByPk($this->id_lastanswer);
        return null;
    }

    public function addAnswer($id_quest, $id_answer) {
        $id_quest = (int) $id_quest;
        $id_answer = (int) $id_answer;
        $this->id_lastanswer = $id_answer;
        $this->questions[$id_quest] = $id_answer;
        /*Answer::model()->findByPk($id_answer)->initAlters($this->getAlternatives());*/
        $this->updateDivisions($id_quest, $id_answer);
    }

    /**
     * Поиск следующего вопроса
     */
    public function nextquest() {
        $nextquest = null;
        $id_leader_alter = $this->getIdLeaderAlterInDivision();
        // поиск дополнительного вопроса
        if (!is_null($answer = $this->getLastAnswer())) {
            $nextquest = $answer->getAdditionQuest($id_leader_alter);
        }
        // инициализация следующего вопроса
        if (is_null($nextquest)) {
            $id_needed_division = $this->getIdNeededDivision();
            $nextquest = Quest::findMaxProbability($id_leader_alter, array_keys($this->questions), $id_needed_division);
        }
        return $nextquest;
    }
}