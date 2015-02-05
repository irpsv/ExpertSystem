<?php

/**
 * This is the model class for table "Division".
 *
 * The followings are the available columns in table 'Division':
 * @property integer $id
 * @property string $name
 *
 * The followings are the available model relations:
 * @property Quest[] $quests
 */
class Division extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'Division';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('name', 'required'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'quests' => array(self::MANY_MANY, 'Quest', 'DivisionQuest(id_division, id_quest)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
		);
	}
        
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Division the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /**
     * Возвращает список имен разделов с их ID
     */
    public static function getNames() {
        $sql = "SELECT id, name FROM Division";
        $names = array();
        foreach (Yii::app()->db->createCommand($sql)->queryAll() as $row) {
            $names[$row['id']] = $row['name'];
        }
        return $names;
    }
}
