<?php

/**
 * This is the model class for table "Link".
 *
 * The followings are the available columns in table 'Link':
 * @property integer $id
 * @property integer $id_parent
 * @property integer $id_type
 * @property double $LN
 * @property double $LS
 *
 * The followings are the available model relations:
 * @property TypeLink $type
 * @property Hypothesis $parent
 */
class Link extends CActiveRecord
{
    //
    // ЛОГИКА
    //
    
    /**
     * Вычисление значения связи
     * @return double коэффициент связи (k)
     */
    public function calcK() {
        $c = 0;
        $values = $this->cvalueChildHypothesis();
        if ($this->id_type == TypeLink::TYPE_LOGIC_AND) {
            $c = min($values);
        }
        else if ($this->id_type == TypeLink::TYPE_LOGIC_OR) {
            $c = max($values);
        }
        else {
            $c = $values[0];
        }
        $LX = $this->LS;
        if ($c < 0) {
            $LX = $this->LN;
            $c = -$c;
        }
        return pow($LX, $c / 5);
    }
    
    /**
     * Возвращает веса дочерних гипотез связи
     * @return double[]
     */
    public function cvalueChildHypothesis() {
        $result = array();
        $sql = "SELECT id_hypothesis FROM LinkChildHyps WHERE id_link = ". intval($this->id);
        $rows = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($rows as $row) {
            $result[] = Hypothesis::model()->findByPk($row['id_hypothesis'])->c_value;
        }
        unset($rows);
        return $result;
    }
    
    /**
     * Возвращает родительские связи
     * @param integer $id_hypothesis ID вопроса для которого нужно найти родительские связи
     */
    public static function listLinksIds($id_hypothesis) {
        $result = array();
        $sql = "SELECT id_link FROM LinkChildHyps WHERE id_hypothesis = ". intval($id_hypothesis);
        $rows = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($rows as $row) {
            $result[] = $row['id_link'];
        }
        unset($rows);
        return $result;
    }
    
    //
    // БАЗА
    //
    
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'Link';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('id_parent, id_type, LN, LS', 'required'),
			array('id_parent, id_type', 'numerical', 'integerOnly'=>true),
			array('LN, LS', 'numerical'),
		);
	}
        
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'type' => array(self::BELONGS_TO, 'TypeLink', 'id_type'),
			'parent' => array(self::BELONGS_TO, 'Hypothesis', 'id_parent'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_parent' => 'Id Parent',
			'id_type' => 'Id Type',
			'LN' => 'Ln',
			'LS' => 'Ls',
		);
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public function listChildsHypsToString() {
            $result = "";
            foreach ($this->getChildHyps() as $h) {
                $result .= "{$h->name}, ";
            }
            return $result;
        }
        
    /**
     * 
     * @param bool $isOnlyId флаг определяющий возвращать объекты (false) или только ID (true)
     */
    public function getChildHyps($isOnlyId = false) {
        if ($this->isNewRecord) {
            return array();
        }
        $sql = "SELECT id_hypothesis AS id FROM LinkChildHyps WHERE id_link = {$this->id}";
        $rows = Yii::app()->db->createCommand($sql)->queryAll();
        $result = array();
        foreach ($rows as $row) {
            $result[] = $isOnlyId ? $row['id'] : Hypothesis::model()->findByPk($row['id']);
        }
        unset($rows);
        return $result;
    }
}