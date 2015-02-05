<?php

/**
 * This is the model class for table "Answer".
 *
 * The followings are the available columns in table 'Answer':
 * @property integer $id
 * @property integer $id_quest
 * @property string $name
 *
 * The followings are the available model relations:
 * @property Quest $quest
 * @property Probability[] $probabilities
 */
class Answer extends CActiveRecord
{
    public function tableName() {
        return 'Answer';
    }

    public function rules() {
        return array(
            array('id_quest, name', 'required'),
            array('id_quest', 'numerical', 'integerOnly'=>true),
        );
    }

    public function relations() {
        return array(
            'quest' => array(self::BELONGS_TO, 'Quest', 'id_quest'),
            'probabilities' => array(self::HAS_MANY, 'Probability', 'id_answer'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'id_quest' => 'Id Quest',
            'name' => 'Name',
        );
    }

    /**
     * Вычисление вероятности для альтернатив после ответа (апостериорных)
     * @param array $alters массив в формате 'id_alter' => 'значение вероятности'
     */
    public function initAlters(array & $alters) {
        // массив значений числителя (чтобы не бегать по 2 раза)
        $top = array();
        // вычисляем знаменатель
        $down = 0.0;
        foreach ($this->probabilities as $p) {
            $value = $p->value * $alters[$p->id_alter];
            $top[$p->id_alter] = $value;
            $down += $value;
        }
        // вычисляем вероятность
        foreach (array_keys($alters) as $id_alter) {
            $alters[$id_alter] = $top[$id_alter] / $down;
        }
    }
    
    /**
     * Вычисление вероятности для альтернатив после ответа (апостериорных)
     * @param array $alters массив в формате:
     * array(
     *      'id_alter' => array(
     *          'prob' => 'вероятность',
     *      ),
     * ),
     * 
     */
    public function initAltersEx(array & $alters) {
        // массив значений числителя (чтобы не бегать по 2 раза)
        $top = array();
        // вычисляем знаменатель
        $down = 0.0;
        foreach ($this->probabilities as $p) {
            $value = $p->value * $alters[$p->id_alter]['prob'];
            $top[$p->id_alter] = $value;
            $down += $value;
        }
        // вычисляем вероятность
        foreach (array_keys($alters) as $id_alter) {
            $alters[$id_alter]['prob'] = $top[$id_alter] / $down;
        }
    }
    
    /**
     * Возвращает дополнительный вопрос
     * @param integer $id_alter
     */
    public function getAdditionQuest($id_alter) {
        foreach ($this->probabilities as $p) {
            if ($p->id_alter == $id_alter) {
                return $p->addition_quest;
            }
        }
        return null;
    }
    
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * Создает запись, либо выгружает из базы если запись с указанным ID уже существует
     * @param integer $pk ID записи
     */
    public static function create($pk)
    {
        $record = self::model()->findByPk($pk);
        return is_null($record) ? new Answer() : $record;
    }
    
    /**
     * Возвращает варианты ответов для вопроса
     * @param integer $id_quest
     */
    public static function findByQuest($id_quest) {
        $sql = 'SELECT id, name FROM Answer WHERE id_quest = ?';
        return Yii::app()->db->createCommand($sql)->queryAll(true, array($id_quest));
    }
}