<?php

/**
 * This is the model class for table "Hypothesis".
 *
 * The followings are the available columns in table 'Hypothesis':
 * @property integer $id
 * @property string $name
 * @property double $odds
 * @property double $c_value
 *
 * The followings are the available model relations:
 * @property Link[] $childLinks
 */
class Hypothesis extends CActiveRecord
{
    //
    // ЛОГИКА
    //
    
    private $_k = null;
    private function getK() {
        if (is_null($this->_k)) {
            $this->_k = 1.0;
            foreach ($this->childLinks as $link) {
                $this->_k *= $link->calcK();
            }
        }
        return $this->_k;
    }
    
    /**
     * Возвращает еще не заданные вопросы для данной гипотезы
     * @return Quest[] не заданные вопросы, либо пустой массив если вопрос нет
     */
    public function getQuestionsNotAsked() {
        $questions = array();
        $sql = "SELECT id FROM NotAskedQuest";
        $rows = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($rows as $row) {
            $questions[] = Quest::model()->findByPk($row['id']);
        }
        unset($rows);
        return $questions;
    }
    
    /**
     * Возвращает коэффициенты уравнения прямой
     */
    private function getParametersLine() {
        $sql = "SELECT a,b FROM ArgumentsHypothesis WHERE id_hypothesis = ". $this->id;
        return Yii::app()->db->createCommand($sql)->queryRow();
    }
    
    /**
     * Вычисляет шансы (ODDS) для данной гипотезы
     */
    private function calcOdds() {
        $this->odds *= $this->getK();
    }
    
    /**
     * Вычисляет значение веса (С)
     */
    private function calcCvalue() {
        $params = $this->getParametersLine();
        $a = $params['a'];
        $b = $params['b'];
        $this->c_value = $a * log($this->getK()) + $b;
    }
    
    /**
     * Возвращает родительские гипотезы
     */
    public function getParents() {
        $result = array();
        $sql = "SELECT id_link FROM LinkChildHyps WHERE id_hypothesis = ". intval($this->id);
        $rows = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($rows as $row) {
            $result[] = Link::model()->findByPk($row['id_link'])->parent;
        }
        unset($rows);
        return $result;
    }
    
    /**
     * Изменение шансов и весов для всех родителей
     * @param integer $id_hyp   ID целевой гипотезы, до которой производиться подьем,
     *                          если 'null', то подьем будет проводиться до корня
     */
    public function moveToUp($id_hyp = null) {
        if (get_class($this) != "Quest") {
            $this->calcOdds();
            $this->calcCvalue();
            $this->save();
        }
        // если мы дошли до целевой альтернативы, то выходим
        if (!is_null($id_hyp) && $this->id == $id_hyp) {
            return;
        }
        // иначе идем дальше вверх
        foreach ($this->getParents() as $hypothesis) {
            $hypothesis->moveToUp($id_hyp);
        }
    }
    
    //
    // БАЗА
    //
    public function tableName()
    {
        return 'Hypothesis';
    }
    
    public function rules()
    {
        return array(
            array('name, odds, c_value', 'required'),
            array('odds, c_value', 'numerical'),
        );
    }

    public function relations()
    {
        return array(
            'childLinks' => array(self::HAS_MANY, 'Link', 'id_parent'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'odds' => 'Odds',
            'c_value' => 'C Value',
        );
    }
    
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public static function listNames() {
        $list = array();
        $sql = "SELECT id, name FROM Hypothesis ORDER BY name";
        $rows = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($rows as $row) {
            $list[$row['id']] = $row['name'];
        }
        return $list;
    }
}