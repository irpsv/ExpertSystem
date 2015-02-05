<?php

return array(
    "name" => "PROSPECTOR",
    "sourceLanguage" => "ru",
    "language" => "ru",
    "import" => array(
        "application.forms.*",
        "application.models.*",
        "application.components.*",
    ),
    "defaultController" => "Site",
    'preload'=>array('log'),
    "components" => array(
        "db" => array(
            "connectionString" => "sqlite:protected/db/sn.db",
        ),
        "log" => array(
            "class" => "CLogRouter",
            "routes" => array(
                'class'=>'CProfileLogRoute',
            ),
        ),
        "errorHandler" => array(
            "errorAction" => "site/error",
        ),
        "urlManager" => array(
            "urlFormat" => "path",
            "showScriptName" => false,
            "rules" => array(
                "hyp/<act:\w+>" => "manager/hypothesis/<act>",
                "link/<act:\w+>" => "manager/link/<act>",
                "quest/<act:\w+>" => "manager/quest/<act>",
            ),
        ),
    ),
    "modules" => array(
        "manager",
        "prospector",
        "tezaurus",
        "gii" => array(
            "class" => "system.gii.GiiModule",
            "password" => "081293",
        ),
    ),
);