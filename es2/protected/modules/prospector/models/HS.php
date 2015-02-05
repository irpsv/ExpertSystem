<?php

/**
 * This is the model class for table "HS".
 *
 * The followings are the available columns in table 'HS':
 * @property integer $id_hypothesis
 * @property integer $id_symbol
 *
 * The followings are the available model relations:
 */
class HS extends CActiveRecord
{
    public function tableName()
    {
        return 'HS';
    }

    public function rules()
    {
        return array(
            array('id_hypothesis, id_symbol', 'required'),
            array('id_hypothesis, id_symbol', 'numerical', 'integerOnly'=>true),
        );
    }

    public function getListSymbols($isToString = true) {
        $sql = "SELECT id_symbol FROM HS WHERE id_hypothesis = {$this->id_hypothesis}";
        $rows = Yii::app()->db->createCommand($sql)->queryAll();
        $symbols = array();
        foreach ($rows as $row) {
            $symbols[] = Symbol::model()->findByPk($row['id_symbol'])->name;
        }
        unset($rows);
        if ($isToString) {
            return implode(", ", $symbols);
        }
        return $symbols;
    }
    
    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'symbol' => array(self::BELONGS_TO, 'Symbol', 'id_symbol'),
            'hypothesis' => array(self::BELONGS_TO, 'Hypothesis', 'id_hypothesis'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id_hypothesis' => 'Id Hypothesis',
            'id_symbol' => 'Id Symbol',
        );
    }
    
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}