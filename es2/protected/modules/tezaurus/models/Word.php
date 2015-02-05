<?php

/**
 * This is the model class for table "Word".
 *
 * The followings are the available columns in table 'Word':
 * @property integer $id
 * @property integer $id_symbol
 * @property string $value
 *
 * The followings are the available model relations:
 * @property Symbol $idSymbol
 */
class Word extends CActiveRecord
{
    //
    // МОДЕЛЬ
    //
    
    /**
     * Список слов для понятия
     * @param integer $id_symbol
     */
    public static function listValues($id_symbol) {
        $result = array();
        $sql = "SELECT id, value FROM Word WHERE id_symbol = ". intval($id_symbol);
        $rows = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($rows as $row) {
            $result[$row['id']] = $row['value'];
        }
        unset($rows);
        return $result;
    }    
    
    /**
     * Поверяет существует ли данное слово в базе или нет
     * @param string $word
     */
    public static function exist($word) {
        $sql = "SELECT * FROM Word WHERE LOWER(value) LIKE LOWER(?)";
        return Yii::app()->db->createCommand($sql)->queryScalar(array($word));
    }
    
    /**
     * Удаление всех слов для понятия
     * @param integer $id_symbol
     */
    public static function removeAllWordsForSymbol($id_symbol) {
        $sql = "DELETE FROM Word WHERE id_symbol = ?";
        Yii::app()->db->createCommand($sql)->execute(array($id_symbol));
    }
    
    //
    // БАЗА
    //
    
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'Word';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('id_symbol, value', 'required'),
			array('id_symbol', 'numerical', 'integerOnly'=>true),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'idSymbol' => array(self::BELONGS_TO, 'Symbol', 'id_symbol'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_symbol' => 'Id Symbol',
			'value' => 'Value',
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Word the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}