<?php

class ProspectorModule extends CWebModule
{
    public function init()
    {
        $this->setImport(array(
            'prospector.models.*',
            'prospector.classes.*',
        ));
    }

    public function beforeControllerAction($controller, $action)
    {
        return parent::beforeControllerAction($controller, $action);
    }
}
