<?php

Yii::import("prospector.models.*");

class ExpertSystem {
    /**
     * Семантическая сеть
     * @var SemanticNetwork 
     */
    private $semanticNetwork;
    
    public static function create(array $target) {
        $es = new ExpertSystem();
        $es->semanticNetwork = SemanticNetwork::create($target);
        return $es;
    }

    public static function load() {
        $es = new ExpertSystem();
        $es->semanticNetwork = SemanticNetwork::load();
        return $es;
    }
    
    public function destroy() {
        SemanticNetwork::destroy();
    }

    public function save() {
        $this->semanticNetwork->save();
    }
    
    /**
     * Слудующий вопрос
     * @return Quest объект вопроса, либо NULL если вопросов нет
     */
    public function nextQuest() {
        return $this->semanticNetwork->reversePassage();
    }
    
    /**
     * Добавление ответа
     * @param integer $id_quest ID вопроса
     * @param real $c_value значение веса
     */
    public function addAnswer($id_quest, $c_value) {
        $this->semanticNetwork->directPassage($id_quest, $c_value);
    }
    
    public function getInfo() {
        $ids_hyp = array_keys($this->semanticNetwork->getTargetHypothesis());
        $result = array();
        foreach ($ids_hyp as $id) {
            $h = Hypothesis::model()->findByPk($id);
            $result[$h->name] = $h->c_value;
        }
        return $result;
    }
}