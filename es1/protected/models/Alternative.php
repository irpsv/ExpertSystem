<?php

/**
 * This is the model class for table "Alternative".
 *
 * The followings are the available columns in table 'Alternative':
 * @property integer $id
 * @property string $name
 *
 * The followings are the available model relations:
 * @property Probability[] $probabilities
 */
class Alternative extends CActiveRecord
{
    public function tableName() {
            return 'Alternative';
    }

    public function rules() {
        return array(
            array('name', 'required'),
        );
    }
    
    public function relations() {
        return array(
            'probabilities' => array(self::HAS_MANY, 'Probability', 'id_alter'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Name',
        );
    }

    public static function model($className=__CLASS__) {
        return parent::model($className);
    }
    
    /**
     * Возвращает список альтернатив
     * @return array список альтернатив
     */
    public static function queryAll() {
        return Yii::app()->db->createCommand('SELECT * FROM Alternative')->queryAll();
    }

    public static function getNames() {
        $sql = "SELECT id, name FROM Alternative";
        $names = array();
        foreach (Yii::app()->db->createCommand($sql)->queryAll() as $row) {
            $names[$row['id']] = $row['name'];
        }
        return $names;
    }

}