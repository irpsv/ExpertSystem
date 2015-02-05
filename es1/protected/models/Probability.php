<?php

/**
 * This is the model class for table "Probability".
 *
 * The followings are the available columns in table 'Probability':
 * @property integer $id
 * @property integer $id_answer
 * @property integer $id_alter
 * @property integer $id_addition_quest
 * @property numeric $value
 *
 * The followings are the available model relations:
 * @property Quest $addition_quest
 * @property Alternative $alter
 * @property Answer $answer
 */
class Probability extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'Probability';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('id_answer, id_alter, value', 'required'),
			array('id_answer, id_alter, id_addition_quest', 'numerical', 'integerOnly'=>true),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'addition_quest' => array(self::BELONGS_TO, 'Quest', 'id_addition_quest'),
			'alter' => array(self::BELONGS_TO, 'Alternative', 'id_alter'),
			'answer' => array(self::BELONGS_TO, 'Answer', 'id_answer'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_answer' => 'Id Answer',
			'id_alter' => 'Id Alter',
			'id_addition_quest' => 'Id Addition Quest',
			'value' => 'Value',
		);
	}
        
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Probability the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /**
     * Поиск записи с указанными параметрами, либо создание если такой записи нет
     * @param integer $id_answer
     * @param integer $id_alter
     */
    public static function create($id_answer, $id_alter) {
        $record = Probability::model()->find("id_answer = :id_answer AND id_alter = :id_alter", array(
            ":id_answer" => $id_answer,
            ":id_alter" => $id_alter,
        ));
        if (is_null($record)) {
            $record = new Probability();
            $record->id_alter = $id_alter;
            $record->id_answer = $id_answer;
        }
        return $record;
    }

}
