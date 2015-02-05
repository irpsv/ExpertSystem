<?php

/**
 * Контроллер для работы с куками.
 * Имеет два открытых метода: save, load, reset
 * Доступ к элементам осуществляется с помощью оператора '->'
 */
class CoockieController {
    /**
     * Данные кук
     * @var array
     */
    private $data = array();
    public function __set($name, $value) {
        $this->data[$name] = $value;
    }
    public function __get($name) {
        return isset($this->data[$name]) ? $this->data[$name] : null;
    }
    
    /**
     * Сохранаяет элементы сохраненные для данного объекта
     */
    public function save() {
        foreach ($this->data as $name => $value) {
            $val = is_array($value) ? json_encode($value) : strval($value);
            Yii::app()->request->cookies[$name] = new CHttpCookie($name, $val);
        }
    }
    
    /**
     * Загружает из кук указанные элементы
     * @param array $names имена выгружаемых элементов
     */
    public function load(array $names) {
        /* @var $c CHttpCookie */
        $c = Yii::app()->request->cookies;
        foreach ($names as $name) {
            if (isset($c[$name])) {
                $this->$name = json_decode($c[$name]->value, true);
            }
        }
    }
    
    /**
     * Отчищает cookie с указаными элементами
     * @param array $names
     */
    public function reset(array $names) {
        foreach ($names as $name) {
            if (isset(Yii::app()->request->cookies[$name]))
                unset(Yii::app()->request->cookies[$name]);
        }
    }
}