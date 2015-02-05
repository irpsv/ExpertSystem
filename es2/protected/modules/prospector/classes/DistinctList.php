<?php

/**
 * Структура реализующая список без повторений
 */
class DistinctList {
    private $data;
    
    public function __construct() {
        $this->data = array();
    }
    
    public function add($value) {
        if (!in_array($value, $this->data)) {
            $this->data[] = $value;
        }
    }
    
    public function addRange(array $values ) {
        foreach ($values as $value) {
            $this->add($value);
        }
    }

    public function data() {
        return $this->data;
    }
}