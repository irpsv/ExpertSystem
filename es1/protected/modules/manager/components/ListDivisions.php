<?php

/**
 * Виджет для выбора категорий вопросаы
 */
class ListDivisions extends CWidget
{
    /**
     * Список разделов
     * @var Division[] 
     */
    public $divisions;    
    /**
     * Список выбранных разделов для указанного вопроса, либо NULL если запись пустая
     * @var array 
     */
    private $listIdSelectedDivisions;
    private function initListSelectDivisions() {
        $this->listIdSelectedDivisions = array();
        foreach ($this->divisions as $div) {
            $this->listIdSelectedDivisions[] = $div->id;
        }
    }
    /**
     * Массив всех имеющихся разделов
     * @var array 
     */
    private $listDivisions;
    private function initListDivisions() {
        $this->listDivisions = array();
        $data = Yii::app()->db->createCommand('SELECT * FROM Division')->queryAll();
        foreach ($data as $row) {
            $this->listDivisions[$row['id']] = $row['name'];
        }
        unset($data);
    }
    public function getListDivisions() {
        return $this->listDivisions;
    }
    
    public function run() {
        $this->initListSelectDivisions();
        $this->initListDivisions();
        $this->render('listdivisions');
    }
    
    /**
     * Выводит INPUT указанного раздела
     */
    public function renderInput($id_division) {
        $checked = in_array($id_division, $this->listIdSelectedDivisions)
                ? 'checked'
                : '';
        return "<input id='division-{$id_division}' name='Division[{$id_division}]' type='checkbox' value='{$id_division}' {$checked} />";
    }
}