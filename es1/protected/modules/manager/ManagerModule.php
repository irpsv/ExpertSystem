<?php

class ManagerModule extends CWebModule
{
    public function init()
    {
        $this->setImport(array(
            'application.models.*',
            'manager.components.*',
        ));
    }

    /**
     * 
     * @param CController $controller
     * @param type $action
     * @return boolean
     */
    public function beforeControllerAction($controller, $action)
    {
        $controller->layout = 'manager.views.layouts.main';
        if(parent::beforeControllerAction($controller, $action))
            return true;
        else
            return false;
    }
}