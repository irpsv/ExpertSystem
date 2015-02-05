<?php

/**
 * This is the model class for table "Predicat".
 *
 * The followings are the available columns in table 'Predicat':
 * @property integer $id
 * @property string $name
 *
 * The followings are the available model relations:
 * @property ParamPredicat[] $paramPredicats
 */
class Predicat extends CActiveRecord
{
    //
    // ЛОГИКА
    //
    
    /**
     * Установка связи аргументов с предикатами
     * @param integer $id_symbol_arg1
     * @param integer $id_symbol_arg2
     */
    public function linkArguments($id_symbol_arg1, $id_symbol_arg2) {
        if ($this->existLink($id_symbol_arg1, $id_symbol_arg2)) {
            return;
        }
        $sql = "INSERT INTO ParamPredicat VALUES(:id_p, :id_arg1, :id_arg2)";
        $cmd = Yii::app()->db->createCommand($sql);
        $cmd->bindValue(":id_p", $this->id);
        $cmd->bindValue(":id_arg1", $id_symbol_arg1);
        $cmd->bindValue(":id_arg2", $id_symbol_arg2);
        $cmd->execute();
    }
    
    private function existLink($id_symbol_arg1, $id_symbol_arg2) {
        $sql = "SELECT * FROM ParamPredicat WHERE id_predicat = :id_p AND id_symbol_arg1 = :id_arg1 AND id_symbol_arg2 = :id_arg2";
        $cmd = Yii::app()->db->createCommand($sql);
        $cmd->bindValue(":id_p", $this->id);
        $cmd->bindValue(":id_arg1", $id_symbol_arg1);
        $cmd->bindValue(":id_arg2", $id_symbol_arg2);
        return ($cmd->queryRow() == false) ? false : true;
    }
    
    public function getFirstArg() {
        return $this->getArgument(1);
    }
    
    public function getSecondArg() {
        return $this->getArgument(2);
    }
    
    private function getArgument($num) {
        $column = "id_symbol_arg" .($num == 1 ? 1 : 2);
        $sql = "SELECT {$column} FROM ParamPredicat WHERE id_predicat = {$this->id}";
        $row = Yii::app()->db->createCommand($sql)->queryRow();
        return ($row == false) ? null : $row[$column];
    }
    
    public function getArgumentsToString() {
        return "ШТА?!";
    }
    
    //
    // БАЗА
    //
    
    public function tableName()
    {
        return 'Predicat';
    }

    public function rules()
    {
        return array(
            array('name', 'required'),
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

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
    
}