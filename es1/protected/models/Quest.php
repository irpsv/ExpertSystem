<?php

function substraction(array & $a, array & $b) {
    foreach (array_intersect($b, $a) as $item) {
        unset($b[array_search($item, $b)]);
        unset($a[array_search($item, $a)]);
    }
}

/**
 * This is the model class for table "Quest".
 *
 * The followings are the available columns in table 'Quest':
 * @property integer $id
 * @property integer $id_division
 * @property string $name
 *
 * The followings are the available model relations:
 * @property Answer[] $answers
 * @property Division[] $divisions
 */
class Quest extends CActiveRecord
{
    public function tableName()
    {
            return 'Quest';
    }
    
    public function rules()
    {
            return array(
                    array('name', 'required'),
            );
    }

    public function relations()
    {
        return array(
            'answers' => array(self::HAS_MANY, 'Answer', 'id_quest'),
            'divisions' => array(self::MANY_MANY, 'Division', 'DivisionQuest(id_quest, id_division)'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'id_division' => 'Id Division',
            'name' => 'Name',
        );
    }

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public static function findMaxProbability($id_leader_alter, $questionsIds, $id_division)
    {
        $id_questes = self::findOfDivision($id_division);
        
        substraction($id_questes, $questionsIds);
        
        $cmd = Yii::app()->db->createCommand();
        $cmd->select('id_quest, MAX(value)');
        $cmd->from('QAP');
        $cmd->where('id_alter = :id_alter', array(":id_alter" => $id_leader_alter));
        $cmd->andWhere(array('in', 'id_quest', $id_questes));
        if (!empty($questionsIds)) {
            $cmd->andWhere(array('not in', 'id_quest', $questionsIds));
        }
        $row = $cmd->queryRow();
        return ($row == false) ? null : self::model()->findByPk($row['id_quest']);
    }
    
    /**
     * Возвращает список вопросов для указанного раздела
     * @param integer $id_division
     */
    public static function findOfDivision($id_division) {
        $sql = "SELECT id_quest FROM DivisionQuest WHERE id_division = ?";
        $result = array();
        foreach (Yii::app()->db->createCommand($sql)->queryAll(true, array($id_division)) as $row) {
            $result[] = (int) $row['id_quest'];
        }
        return $result;
    }
    
    /**
     * Возвращает список разделов для указанного вопроса
     * @param int $id_quest
     * @return array список ID разделов
     */
    public static function getDivisions($id_quest)
    {
        $sql = "SELECT id_division FROM DivisionQuest WHERE id_quest = ?";
        $data = Yii::app()->db->createCommand($sql)->queryAll(true, array($id_quest));
        $result = array();
        foreach ($data as $row) { $result[] = $row['id_division']; };
        return $result;
    }
}