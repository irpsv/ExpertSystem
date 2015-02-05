<?php

/**
 * Вопрос, наследуется от Гипотезы, и в отличии от нее имеет ответы на вопросы
 */
class Quest extends Hypothesis
{
    /**
     * Ответы для данного вопроса
     * @return Answer[]
     */
    public static function getAnswersEx($id_quest) {
        $answers = array();
        $sql = "SELECT id FROM Answer WHERE id_hypothesis = ". intval($id_quest);
        $rows = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($rows as $row) {
            $answers[] = Answer::model()->findByPk($row['id']);
        }
        unset($rows);
        return $answers;
    }
    
    public function getAnswers() {
        return self::getAnswersEx($this->id);
    }
    
    /**
     * Возвращает ответ для данного вопроса с указанным значением
     * @param string $type 'min' | 'max' 
     */
    public function getAnswerWeight($type) {
        $agr = ($type == 'min') ? "MIN" : "MAX";
        $c = new CDbCriteria();
        $c->select = "id, {$agr}(c_value)";
        $c->group = 'id';
        $c->compare("id_hypothesis", $this->id);
        return Answer::model()->find($c);
    }
    
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}