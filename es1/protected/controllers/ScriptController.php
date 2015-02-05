<?php

class ScriptController extends CController
{
    public static function importBootstrap()
    {
        /* @var $cs CClientScript */
        $cs = Yii::app()->clientScript;
        $bu = Yii::app()->baseUrl .'/bootstrap';
        $cs->registerCssFile($bu .'/css/bootstrap.css');
        $cs->registerCssFile($bu .'/css/bootstrap-theme.css');
        $cs->registerScriptFile($bu .'/js/bootstrap.min.js');
    }
}