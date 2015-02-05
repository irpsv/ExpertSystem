<?php

$yii = (__DIR__).'/framework/yii.php';
$config = (__DIR__).'/protected/config/main.php';

require_once($yii);
Yii::createWebApplication($config)->run();