<?php

/**
 * This is the model class for table "Answer".
 *
 * The followings are the available columns in table 'Answer':
 * @property integer $id
 * @property integer $id_hypothesis
 * @property string $name
 * @property integer $c_value
 *
 * The followings are the available model relations:
 * @property Hypothesis $quest
 */
class Answer extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'Answer';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('id_hypothesis, name, c_value', 'required'),
			array('id_hypothesis, c_value', 'numerical', 'integerOnly'=>true),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
            return array(
                'quest' => array(self::BELONGS_TO, 'Hypothesis', 'id_hypothesis'),
            );
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_hypothesis' => 'Id Quest',
			'name' => 'Name',
			'c_value' => 'C Value',
		);
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /**
     * Возвращает максимальный идентификатор в таблице ответов
     */
    public static function getMaxId() {
        $sql = "SELECT MAX(id) AS id FROM Answer";
        return Yii::app()->db->createCommand($sql)->queryRow()['id'];
    }
}