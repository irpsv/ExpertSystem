<?php

/**
 * This is the model class for table "Symbol".
 *
 * The followings are the available columns in table 'Symbol':
 * @property integer $id
 * @property integer $id_parent
 * @property string $name
 * @property string $desc
 *
 * The followings are the available model relations:
 */
class Symbol extends CActiveRecord
{
    //
    //ЛОГИКА
    //
    
    public static function listNames() {
        $names = array();
        $sql = "SELECT id, name FROM Symbol ORDER BY name ASC";
        $rows = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($rows as $row) {
            $names[$row['id']] = $row['name'];
        }
        unset($rows);
        return $names;
    }
    
    /**
     * Список 
     * @param array $ids_symbol
     */
    public static function listDivisionNames(array $ids_symbol) {
        $result = array();
        foreach ($ids_symbol as $id_symbol) {
            $sql = "SELECT DISTINCT id, name FROM Division WHERE id IN (SELECT id_division FROM DS WHERE id_symbol = {$id_symbol})";
            $rows = Yii::app()->db->createCommand($sql)->queryAll();
            foreach ($rows as $row) {
                $result[$row['id']] = $row['name'];
            }
            unset($rows);
        }
        return $result;
    }
    
    //
    //БАЗА
    //
    
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'Symbol';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('name', 'required'),
                        array('id_parent', 'numerical', "integerOnly" => true),
                        array('desc', 'safe'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
                        "id_parent" => "Родительское понятие",
			'name' => 'Value',
                        "desc" => "Описание"
		);
	}
        
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}