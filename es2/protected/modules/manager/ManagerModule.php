<?php

class ManagerModule extends CWebModule
{
    public function init()
    {
        $this->setImport(array(
            'prospector.models.*',
            'tezaurus.models.*',
            'application.controllers.CrudController',
            'manager.components.*',
            'manager.forms.*',
        ));
    }

    public function beforeControllerAction($controller, $action)
    {
        $controller->layout = "manager.views.layouts.main";
        return parent::beforeControllerAction($controller, $action);
    }
}
