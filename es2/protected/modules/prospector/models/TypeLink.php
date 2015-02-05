<?php

/**
 * This is the model class for table "TypeLink".
 *
 * The followings are the available columns in table 'TypeLink':
 * @property integer $id
 * @property string $name
 *
 * The followings are the available model relations:
 * @property Link[] $links
 */
class TypeLink extends CActiveRecord
{
    const TYPE_LOGIC_AND = 2;
    const TYPE_LOGIC_OR = 1;
    const TYPE_PRODUCTION = 3;
    
    public function tableName()
    {
        return 'TypeLink';
    }

    public function rules()
    {
        return array(
            array('name', 'required'),
        );
    }

    public function relations()
    {
        return array(
            'links' => array(self::HAS_MANY, 'Link', 'id_type'),
        );
    }

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

    public static function listNames() {
        $list = array();
        $sql = "SELECT id, name FROM TypeLink";
        $rows = Yii::app()->db->createCommand($sql)->queryAll();
        foreach ($rows as $row) {
            $list[$row['id']] = $row['name'];
        }
        return $list;
    }
}
