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
    
    public function getListNamesWordsToString() {
        return implode(",", $this->getListNamesWords());
    }
    
    public function getListNamesWords() {
        $result = array();
        $sql = "SELECT value FROM Word WHERE id_symbol = {$this->id}";
        $rows = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($rows as $row) {
            $result[] = $row['value'];
        }
        unset($rows);
        return $result;
    }
    
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
			'words' => array(self::HAS_MANY, 'Word', 'id_symbol'),
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

    /**
     * Проверяет существует ли связь между каким либо символом и гипотезой
     * @param integer $id_hyp ID гипотезы
     */
    public static function existLinkOfHypothesis($id_hyp) {
        $sql = "SELECT * FROM HS WHERE id_hypothesis = ". intval($id_hyp);
        return !empty(Yii::app()->db->createCommand($sql)->queryRow());
    }
}