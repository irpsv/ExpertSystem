<?php

return array(
    "name" => "ЭС - теорема Байеса",
    "import" => array(
        'application.models.*',
        'application.controllers.CrudController',
        'application.classes.BayesClass',
    ),
    "components" => array(
        "db" => array(
            "connectionString" => "sqlite:protected/data/bz.db",
        ),
        "urlManager" => array(
            "urlFormat" => "path",
            "showScriptName" => false,
            "rules" => array(
                "addanswer" => "expert/addanswer",
                "nextquest" => "expert/nextquest",
                "start" => "expert/start",
                "end" => "expert/end",
            ),
        ),
    ),
    "modules" => array(
        "gii" => array(
            "class" => "system.gii.GiiModule",
            "password" => "081293",
        ),
        "manager",
    ),
    "defaultController" => "Bayes",
);